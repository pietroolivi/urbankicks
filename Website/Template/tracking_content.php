<?php
function getOrderStatusStep($trackingStates) {
    $steps = [
        'placed' => 1,
        'in progress' => 2,
        'shipped' => 3, 
        'delivered' => 4
    ];

    foreach ($trackingStates as $state) {
        if (empty($state['actual_arrival'])) {
            return $steps[strtolower($state['status'])] - 1;
        }
    }

    return 4;
}

if(!isset($templateParams["tracking"])): ?>
    <p>Order tracking information not available.</p>
<?php else: 
    $tracking = $templateParams["tracking"];
    $orderInfo = $tracking['order_info'];
    $trackingStates = $tracking['tracking_states'];
    $products = $tracking['products'];
    $currentStep = getOrderStatusStep($trackingStates);
?>
    <section class="tracking-section">
        <header>
            <a href="javascript:history.back()" class="back">
                <img src="CSS/Images/Icons/back.svg" alt="Icon representing a backward arrow, to return to the previous page." />
            </a>
            <h2>Track Order</h2>
        </header>

        <div class="order-info-tracking">
            <h3>#<?php echo htmlspecialchars($_GET["order"]); ?></h3>
        </div>

        <ul id="products-container" class="products-overview lateral-container">
            <?php foreach($products as $item): ?>
                <li>
                    <article class="product-card">
                        <div class="product-details">
                            <a class="product-link">
                            <img src="CSS/Images/Products/<?php echo htmlspecialchars($item['image']);?>.webp" 
                                alt="<?php echo htmlspecialchars($item['name']); ?>"/></a>
                            <div class="item-info">
                                    <h4><?php echo htmlspecialchars($item['name']);?></h4>
                                    <p >Size: <?php echo htmlspecialchars($item['size']);?> | Qty: <?php echo htmlspecialchars($item['quantity']); ?></p>
                                    <p>Color: <?php echo htmlspecialchars($item['color']);?></p>
                                    <p>€<?php echo number_format($item['price'], 2);?></p>
                            </div>
                        </div>
                    </article>
                </li>
            <?php endforeach; ?>
        </ul>

        <section id="order-details-track">
            <h3>ORDER DETAILS</h3>
            <div class="detail-track-block">
                <p>Expected delivery date</p>
                <?php
                $deliveryEstimate = null;
                foreach($trackingStates as $state) {
                    if(strtolower($state['status']) == 'delivered') {
                        $deliveryEstimate = $state['estimated_arrival'];
                        break;
                    }
                }
                ?>
                <p>
                    <?php if($deliveryEstimate): ?>
                    <?php echo (new DateTime($deliveryEstimate))->format('d M Y'); ?>
                    <?php else: ?>
                        Delivery date not yet available
                    <?php endif; ?>
                </p>
            </div>
            <div class="detail-track-block">
                <p>Tracking ID</p>
                <p><a href="#order-status">TRK<?php echo str_pad($_GET["order"], 9, '0', STR_PAD_LEFT); ?></a></p>
            </div>            
        </section>
        <section id="order-status">                
            <h3>ORDER STATUS</h3>
            <div class="order-tracking">
                <ol>
                <?php
                //we create a map of states for easy access
                $trackingMap = [];
                $allStates = ['Placed', 'In progress', 'Shipped', 'Delivered'];
                foreach($trackingStates as $st) {
                    $trackingMap[$st['status']] = $st; 
                }

                foreach($allStates as $statusKey):
                    // if the state exist we obtain it from the tracking map
                    $state = isset($trackingMap[$statusKey]) ? $trackingMap[$statusKey] : null;


                    if($statusKey === 'Placed') {
                        $isCompleted = true;
                        $actualDate = $orderInfo['order_date']; 
                    } else {
                        $isCompleted = $state && !empty($state['actual_arrival']);
                        $actualDate = $state['actual_arrival'] ?? null;
                    }
                ?>
                    <li class="<?php echo $isCompleted ? 'completed' : ''; ?>">
                    <span class="circle"></span>
                    <div class="status-info">
                        <em><?php echo $statusKey; ?></em>
                        <?php if($isCompleted && $actualDate): ?>
                        <p><?php echo (new DateTime($actualDate))->format('d M Y, h:i A'); ?></p>
                        <?php elseif($state && $state['estimated_arrival']): ?>
                        <p>Expected <?php echo (new DateTime($state['estimated_arrival']))->format('d M Y'); ?></p>
                        <?php else: ?>
                        <p>Expected: Pending</p>
                        <?php endif; ?>
                    </div>
                    </li>
                <?php endforeach; ?>
                </ol>
            </div>
        </section>
        <section class="parcel-loc">
            <h3>PARCEL LOCATION</h3>
            <img src="CSS/Images/Illustrations/map.svg" alt="Map showing parcel location">
        </section>
    </section>
<?php endif; ?>