<?php
require_once __DIR__.'/../../Config/Database.php';
require_once __DIR__.'/../../Models/Menu.php';

$database = new Database();
$db = $database->getConnection();
$menu = new Menu($db);

// Lấy dữ liệu món ăn theo danh mục
$coffees = $menu->getItemsByCategory('coffee')->fetchAll(PDO::FETCH_ASSOC);
$teas = $menu->getItemsByCategory('tea')->fetchAll(PDO::FETCH_ASSOC);
$smoothies = $menu->getItemsByCategory('smoothie')->fetchAll(PDO::FETCH_ASSOC);
$desserts = $menu->getItemsByCategory('dessert')->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Quán Cafe</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/menu.css">
    <?php include './app/Views/shares/header.php'; ?>
</head>
<body>
    <main class="container">
        <h1>Menu Quán Cafe</h1>
        
        <div class="menu-categories">
            <div class="category">
                <h2>Cà Phê</h2>
                <div class="menu-items">
                    <?php foreach ($coffees as $item): ?>
                        <div class="menu-item">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <div class="item-info">
                                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p class="description"><?php echo htmlspecialchars($item['description']); ?></p>
                                <p class="price"><?php echo number_format($item['price'], 0, ',', '.'); ?> đ</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="category">
                <h2>Trà & Trà Sữa</h2>
                <div class="menu-items">
                    <?php foreach ($teas as $item): ?>
                        <div class="menu-item">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <div class="item-info">
                                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p class="description"><?php echo htmlspecialchars($item['description']); ?></p>
                                <p class="price"><?php echo number_format($item['price'], 0, ',', '.'); ?> đ</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="category">
                <h2>Smoothie</h2>
                <div class="menu-items">
                    <?php foreach ($smoothies as $item): ?>
                        <div class="menu-item">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <div class="item-info">
                                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p class="description"><?php echo htmlspecialchars($item['description']); ?></p>
                                <p class="price"><?php echo number_format($item['price'], 0, ',', '.'); ?> đ</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="category">
                <h2>Bánh & Tráng Miệng</h2>
                <div class="menu-items">
                    <?php foreach ($desserts as $item): ?>
                        <div class="menu-item">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <div class="item-info">
                                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p class="description"><?php echo htmlspecialchars($item['description']); ?></p>
                                <p class="price"><?php echo number_format($item['price'], 0, ',', '.'); ?> đ</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>

    <?php include './app/Views/shares/footer.php'; ?>
</body>
</html>