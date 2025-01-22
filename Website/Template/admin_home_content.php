<ul class="general-statistics">
    <li>
        <img src="" alt="">
        <div>
            <p>Orders Completed</p>
            <p><?php echo($templateParams["completed_orders"]); ?></p>
        </div>
    </li>
    <li>
        <img src="" alt="">
        <div>
            <p>Orders Pending</p>
            <p><?php echo($templateParams["pending_orders"]); ?></p>
        </div>
    </li>
    <li>
        <img src="" alt="">
        <div>
            <p>Total Users</p>
            <p><?php echo($templateParams["total_users"]); ?></p>
        </div>
    </li>
    <li>
        <img src="" alt="">
        <div>
            <p>Best Seller</p>
            <p><?php echo($templateParams["best_seller"]); ?></p>
        </div>
    </li>
</ul>

<div class="admin-actions">
    <a href="admin_orders.php" class="admin-button">Orders Received</a>
    <a href="admin_products.php" class="admin-button">Published Products</a>
    <a href="admin_statistics.php" class="admin-button">Statistics</a>
    <a href="admin_messages.php" class="admin-button">Messages</a>
</div>