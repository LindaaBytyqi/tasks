<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Preview</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8fafc; font-family: system-ui, -apple-system, sans-serif; }
        .custom-sidebar { border-radius: 16px; overflow: hidden; }
        .sidebar-category-title { font-size: 0.75rem; letter-spacing: 0.5px; }
        .sidebar-link { border: none; padding: 12px 20px; color: #4b5563; font-weight: 500; transition: all 0.2s ease; text-decoration: none; display: block; }
        .sidebar-link:hover { background-color: #fdf2f8; color: #e681b3; padding-left: 24px; }
        .sidebar-link.active { background-color: #fdf2f8 !important; color: #e681b3 !important; font-weight: 600; border-left: 4px solid #e681b3; }
        .content-card { border-radius: 16px; }
        .info-box { border: 1px solid #e2e8f0; }
        .btn-theme-primary { background-color: #e681b3; border-color: #e681b3; color: #fff; border-radius: 8px; }
        .btn-theme-primary:hover { background-color: #d16f9f; border-color: #d16f9f; color: #fff; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-lg-3 col-md-4">
            <div class="card border-0 shadow-sm custom-sidebar">
                <div class="card-header border-0 bg-dark text-white text-center py-3">
                    <h5 class="mb-0 fw-bold">My Account</h5>
                </div>
                <div class="list-group list-group-flush py-2">
                    <div class="sidebar-category-title text-muted px-3 pt-2 pb-1 text-uppercase fw-bold">
                        👤 Personal Info
                    </div>
                    <a href="#" class="sidebar-link active">
                        <i class="bi bi-person me-2"></i> My Profile
                    </a>
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-key me-2"></i> Change Password
                    </a>
                    <hr class="dropdown-divider my-2">
                    <div class="sidebar-category-title text-muted px-3 pt-2 pb-1 text-uppercase fw-bold">
                        📦 Orders
                    </div>
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-bag-check me-2"></i> My Orders
                    </a>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="col-lg-9 col-md-8">
            <div class="card border-0 shadow-sm content-card">
                <div class="card-header bg-white border-bottom py-3">
                    <h4 class="mb-0 fw-bold text-dark">Personal Information</h4>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-box p-3 rounded bg-light">
                                <span class="text-muted small d-block mb-1">First Name</span>
                                <strong class="fs-5 text-dark">Linda</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box p-3 rounded bg-light">
                                <span class="text-muted small d-block mb-1">Last Name</span>
                                <strong class="fs-5 text-dark">Krasniqi</strong>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-box p-3 rounded bg-light">
                                <span class="text-muted small d-block mb-1">Email Address</span>
                                <strong class="fs-5 text-dark">linda@example.com</strong>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pt-2">
                        <button class="btn btn-theme-primary px-4 py-2 fw-semibold">
                            <i class="bi bi-pencil-square me-2"></i>Edit Information
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>