<h2>Data Preview</h2>
<ul class="general-statistics">
    <li>
        <div>
            <p>Orders Completed</p>
            <p><?php echo($templateParams["completed_orders"]); ?></p>
        </div>
    </li>
    <li>
        <div>
            <p>Orders Pending</p>
            <p><?php echo($templateParams["pending_orders"]); ?></p>
        </div>
    </li>
    <li>
        <div>
            <p>Total Users</p>
            <p><?php echo($templateParams["total_users"]); ?></p>
        </div>
    </li>
    <li>
        <div>
            <p>Best Seller</p>
            <p><?php echo($templateParams["best_seller"]); ?></p>
        </div>
    </li>
</ul>
<h2>Actions</h2>
<div class="admin-actions">
    <button class="full-button-white" onclick="location.href='admin_orders.php'" class="admin-button">Orders Received</button>
    <button class="full-button-white" onclick="location.href='admin_products.php'" class="admin-button">Published Products</button>
    <button class="full-button-white" onclick="location.href='admin_statistics.php'" class="admin-button">Statistics</button>
    <button class="full-button-white" onclick="location.href='admin_messages.php'" class="admin-button">Messages</button>
</div>