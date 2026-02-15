<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$successMessage = $_SESSION['success'] ?? '';
$errorMessage   = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

/**
 * Breadcrumb items format:
 * - ['label' => 'Home', 'href' => 'dashboard.php'] (link)
 * - ['label' => 'Workspace'] (current page / no link)
 */
if (!function_exists('renderBreadcrumb')) {
    function renderBreadcrumb(array $items): string
    {
        if (count($items) === 0) {
            return '';
        }

        $out = '<nav class="flex items-center gap-3" aria-label="Breadcrumb">';
        $lastIndex = count($items) - 1;

        foreach ($items as $i => $item) {
            $label = htmlspecialchars((string)($item['label'] ?? ''), ENT_QUOTES, 'UTF-8');
            $href  = isset($item['href']) ? (string)$item['href'] : '';

            if ($i > 0) {
                $out .= '<div class="h-1 w-1 rounded-full bg-slate-300"></div>';
            }

            $isLast = ($i === $lastIndex);
            if (!$isLast && $href !== '') {
                $out .= '<a class="text-sm text-slate-500 hover:underline" href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '">' . $label . '</a>';
            } else {
                $out .= '<div class="text-sm font-medium text-slate-700">' . $label . '</div>';
            }
        }

        $out .= '</nav>';
        return $out;
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PT. Samson Kapal Yudhasamudera</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Favicon -->
    <link rel="icon" href="/favicon.ico">

    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

    <!-- Boxicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.4/css/boxicons.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
</head>

<body class="bg-slate-100 text-slate-900">
    <!-- Inject session messages into JS -->
    <script>
        window.APP_MESSAGES = {
            success: <?php echo json_encode($successMessage); ?>,
            error: <?php echo json_encode($errorMessage); ?>,
        };
    </script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="/js/toast.js"></script>
    <script src="/js/main.js"></script>