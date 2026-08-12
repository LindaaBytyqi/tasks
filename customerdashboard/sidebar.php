<style>
.sidebar-card{
    min-height:500px;
    margin-left:100px;
}
.sidebar-item{
    padding:25px 20px !important;
    font-size:18px;
    font-weight:600;
}
.sidebar-subitem{
    padding:22px 35px !important;
    font-size:16px;
}
.orders-item{
    margin-top:25px;
}
.sidebar-card .list-group-item:hover{
    background:#f8fafc;
}
</style>

<div class="card shadow-sm sidebar-card">
    <div class="card-header bg-dark text-white py-4">
        <h5 class="mb-0 text-center">
            My Account
        </h5>
    </div>
    <div class="list-group list-group-flush">
        <a href="#"
           class="list-group-item list-group-item-action sidebar-item">
            👤 Personal Information
        </a>

        <a href="dashboard.php?page=profile"
           class="list-group-item list-group-item-action sidebar-subitem">
            • My Profile
        </a>

        <a href="dashboard.php?page=changepassword"
           class="list-group-item list-group-item-action sidebar-subitem">
            • Change Password
        </a>

        <a href=""
           class="list-group-item list-group-item-action sidebar-item orders-item">
            📦 Orders
        </a>

        <a href="dashboard.php?page=orderdetail"
           class="list-group-item list-group-item-action sidebar-subitem">
            • My Orders
        </a>
    </div>
</div>