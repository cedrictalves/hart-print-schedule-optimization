<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ScheduleController extends Controller
{
    /**
     * Display the production schedule order by need_by_date and product types.
     *
     */
    public function display()
    {

        // Fetch orders, sort them by need_by_date and type in order to optimize production
        $orders = Order::with('items.product')
            ->whereDate('need_by_date', '>=', Carbon::now())
            ->get();

        $orders = $this->smartSortOrders($orders);
        $planning = [];

        // If there are no orders, return an empty schedule
        if (!$orders->isEmpty()) {

            $previousType = null; // To track the previous order type for setup time calculation

            // Loop through each order and its items to calculate production schedule
            foreach ($orders as $order) {

                // Validate that the order has items
                if ($order->items->isEmpty()) {
                    continue; // Skip this order if it has no items
                }

                // Initialize an array to hold order items data
                $order_items_datas = [];

                // Order Type
                $type = $order->type;

                // Assuming we want to set the delivery deadline to one day before the need_by_date if we need to deliver by the need_by_date
                $deliveryDeadline = $this->parseDate($order->need_by_date)->subDay();

                // Add setup time for the first item or if the type changes
                $setupTime = ($previousType === null || $previousType !== $type) ? 30 : 0;

                foreach ($order->items as $item) {

                    // Validate that the product exists
                    if (!$item->product) {
                        continue; // Skip this item product is not found
                    }

                    // Items quantity
                    $quantity = $item->quantity;

                    // Production duration in minutes
                    $durationInMinutes = $this->getProductionTime($type, $item->quantity);
                    if ($durationInMinutes <= 0) {
                        continue; // Skip if no production is returned
                    }

                    // Fill the order items data with product name, quantity, and production time
                    $order_items_datas[] = [
                        'product_name' => $item->product->name,
                        'quantity' => $quantity,
                        'production_time' => $this->getProductionTime($order->type, $item->quantity),
                    ];

                    // Update the previous type to the current one for the next iteration
                    $previousType = $type;
                }

                // Fill the planning array with the order details
                $totalProductionTime = array_sum(array_column($order_items_datas, 'production_time')) + $setupTime;
                $planning[] = [
                    'order_id' => $order->id,
                    'items' => $order_items_datas,
                    'customer_name' => $order->customer_name,
                    'type' => $type,
                    'due_date' => $this->parseDate($order->need_by_date)->format('Y-m-d'),
                    'setup' => $setupTime,
                    'total_production_time' => $totalProductionTime,
                ];

            }
        }

        // Return the schedule view with the planning data
        return view('schedule', ['planning' => $planning]);
    }

    /**
     * Smartly sort orders by need_by_date and type to optimize production.
     *
     * @param \Illuminate\Support\Collection $orders
     * @return \Illuminate\Support\Collection
     */
    private function smartSortOrders($orders)
    {
        // Group orders by need_by_date and sort dates in ascending order
        $groupedByDate = $orders->groupBy(function ($order) {
            return $order->need_by_date;
        })->sortKeys();

        $sorted = collect(); // Initialize a collection to hold the sorted orders

        $previousType = null; // Track the previous type to minimize setup changes

        foreach ($groupedByDate as $date => $ordersForDate) {
            $remaining = $ordersForDate->values(); // Get all orders for this date
            $usedIndexes = []; // Track which orders have already been added

            // While there are orders left to schedule for this date
            while ($remaining->count() > count($usedIndexes)) {
                $nextIndex = null;

                // First, try to find an order that matches the previous type
                foreach ($remaining as $i => $order) {
                    if (in_array($i, $usedIndexes)) continue;
                    if ($order->type === $previousType) {
                        $nextIndex = $i;
                        break;
                    }
                }

                // If no matching type is found, pick the next available order
                if ($nextIndex === null) {
                    foreach ($remaining as $i => $order) {
                        if (!in_array($i, $usedIndexes)) {
                            $nextIndex = $i;
                            break;
                        }
                    }
                }

                // Add the selected order to the sorted list
                if ($nextIndex !== null) {
                    $order = $remaining[$nextIndex];
                    $sorted->push($order);           // Add to final result
                    $usedIndexes[] = $nextIndex;     // Mark as used
                    $previousType = $order->type;    // Update type for next match
                }
            }
        }

        return $sorted;
    }

    /**
     * Return production speed in units per hour for each type.
     *
     * @param int $type - The type of product (1, 2, or 3).
     * @return int - Production speed in units per hour.
     */
    private function getProductionSpeedPerType(int $type): int
    {
        return match ($type) {
            1 => 715,
            2 => 770,
            3 => 1000,
            default => 0,
        };
    }

    /**
     * Return time needed to produce a given number of units.
     *
     * @param int $type - The type of product (1, 2, or 3).
     * @param int $units - The number of units to produce.
     * @return int  - Time in minutes needed to produce the units.
     */
    private function getProductionTime(int $type, int $units): int
    {
        $speed = $this->getProductionSpeedPerType($type);
        return $speed > 0 ? (int) ceil(($units / $speed) * 60) : 0;
    }

    /**
     * Return the setup time in minutes for a given type.
     *
     * @return int
     */
    private function getSetupTime(): int
    {
        return 30; // Fixed setup time of 30 minutes for any type
        // Note : You can update this logic if the setup time varies by type
    }

    /**
     * Parse date with Carbon.
     *
     * @param string $date - The date string to parse.
     * @return Carbon - The formatted date string in 'Y-m-d' format.
     */
    private function parseDate(string $date): Carbon
    {
        return Carbon::parse($date);
    }
}
