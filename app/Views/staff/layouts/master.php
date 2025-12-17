<!DOCTYPE html>
<html lang="vi">
<head>
    </head>
<body>
    <div class="d-flex">
        <?php require_once 'views/staff/layout/sidebar.php'; ?> 
        
        <div id="content" class="flex-grow-1">
            <?php require_once 'views/' . $view . '.php'; ?>
        </div>
    </div>
    
    </body>
</html>