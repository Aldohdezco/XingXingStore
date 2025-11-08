<?php
session_start();
require 'db.php';

/* ---------------- LOGIN ---------------- */
if (isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['username'];
        } else {
            $error = "Usuario o contraseña incorrectos";
        }
    } else {
        $error = "Por favor llena todos los campos.";
    }
}

/* ---------------- REGISTRO ---------------- */
if (isset($_POST['register'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $password]);
            $success = "Usuario registrado correctamente, ya puedes iniciar sesión.";
        } catch (PDOException $e) {
            $error = "El nombre de usuario ya existe.";
        }
    } else {
        $error = "Por favor llena todos los campos.";
    }
}

/* ---------------- LOGOUT ---------------- */
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

/* ---------------- CRUD PRODUCTOS ---------------- */
if (isset($_POST['add_product'])) {
    $stmt = $pdo->prepare("INSERT INTO products (name, type, price, stock, description) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_POST['name'], $_POST['type'], $_POST['price'], $_POST['stock'], $_POST['description']]);
}

if (isset($_POST['edit_product'])) {
    $stmt = $pdo->prepare("UPDATE products SET name=?, type=?, price=?, stock=?, description=? WHERE id=?");
    $stmt->execute([
        $_POST['name'], $_POST['type'], $_POST['price'], $_POST['stock'], $_POST['description'], $_POST['id']
    ]);
    header("Location: index.php");
    exit;
}

if (isset($_POST['delete_product'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id=?");
    $stmt->execute([$_POST['id']]);
}

/* ---------------- BÚSQUEDA ---------------- */
$search = $_GET['search'] ?? '';
$products = $pdo->prepare("SELECT * FROM products WHERE name LIKE ?");
$products->execute(["%$search%"]);
$products = $products->fetchAll();

/* ---------------- EDITAR PRODUCTO ---------------- */
$edit_product = null;
if (isset($_GET['edit_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$_GET['edit_id']]);
    $edit_product = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>XINGXING STORE</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
body {
    background: linear-gradient(135deg, #f9f1ff, #d9f7ff);
    font-family: 'Comic Sans MS', cursive;
}
.nav-link { cursor: pointer; font-weight: bold; }
.nav-pills .nav-link.active { background-color: #ff6f61 !important; }
.card { border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
input, button { border-radius: 8px !important; }
.btn-primary { background-color: #ff6f61; border: none; }
.btn-primary:hover { background-color: #e55c4f; }
.btn-warning { background-color: #ffc107; border: none; border-radius: 8px; }
.btn-warning:hover { background-color: #e0a800; }
.logo {
    height: 50px;
    margin-right: 10px;
    border-radius: 10px;
}
.title-container {
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
</head>
<body>
<div class="container mt-4">

<?php if (!isset($_SESSION['user'])): ?>
    <!-- LOGIN / REGISTRO -->
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card p-4">
                <div class="title-container mb-3">
                    <img src="assets/logo.jpeg" alt="Logo" class="logo">
                    <h3 style="color:#ff6f61;">XINGXING STORE</h3>
                </div>

                <?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
                <?php if (isset($success)) echo "<p class='text-success'>$success</p>"; ?>

                <ul class="nav nav-pills mb-3 justify-content-center">
                    <li class="nav-item"><a class="nav-link active" id="btn-login">Login</a></li>
                    <li class="nav-item"><a class="nav-link" id="btn-register">Registro</a></li>
                </ul>

                <form method="POST" id="login-form">
                    <input type="text" name="username" placeholder="Usuario" class="form-control mb-2" required>
                    <input type="password" name="password" placeholder="Contraseña" class="form-control mb-2" required>
                    <button type="submit" name="login" class="btn btn-primary w-100">Entrar</button>
                </form>

                <form method="POST" id="register-form" style="display:none;">
                    <input type="text" name="username" placeholder="Nuevo usuario" class="form-control mb-2" required>
                    <input type="password" name="password" placeholder="Nueva contraseña" class="form-control mb-2" required>
                    <button type="submit" name="register" class="btn btn-success w-100">Registrar</button>
                </form>
            </div>
        </div>
    </div>

<?php else: ?>
    <!-- SISTEMA DE PRODUCTOS -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center">
            <img src="assets/logo.jpeg" alt="Logo" class="logo">
            <h2 style="color:#ff6f61;">XINGXING STORE</h2>
        </div>
        <a href="?logout=1" class="btn btn-danger">Cerrar sesión</a>
    </div>

    <ul class="nav nav-pills mb-3">
        <li class="nav-item flex-fill text-center">
            <a class="nav-link <?= !$edit_product ? 'active' : '' ?>" id="tab-view">VER PRODUCTOS</a>
        </li>
        <li class="nav-item flex-fill text-center">
            <a class="nav-link <?= $edit_product ? 'active' : '' ?>" id="tab-register">
                <?= $edit_product ? 'EDITAR PRODUCTO' : 'REGISTRAR' ?>
            </a>
        </li>
    </ul>

    <!-- ================= VER PRODUCTOS ================= -->
    <div id="view-section" <?= $edit_product ? 'style="display:none;"' : '' ?>>
        <h3>Ver Productos</h3>
        <form method="GET" class="mb-3">
            <input type="text" name="search" placeholder="Buscar producto..." class="form-control" value="<?= htmlspecialchars($search) ?>">
        </form>
        <table class="table table-bordered table-hover">
            <thead class="table-light text-center">
                <tr>
                    <th>Nombre</th><th>Tipo</th><th>Precio</th><th>Stock</th><th>Descripción</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($products as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['name']) ?></td>
                        <td><?= htmlspecialchars($p['type']) ?></td>
                        <td>$<?= number_format($p['price'], 2) ?></td>
                        <td><?= $p['stock'] ?></td>
                        <td><?= htmlspecialchars($p['description']) ?></td>
                        <td class="text-center">
                            <a href="?edit_id=<?= $p['id'] ?>" class="btn btn-warning btn-sm"><i class="fa-solid fa-pen"></i></a>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                <button type="submit" name="delete_product" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- ================= REGISTRAR / EDITAR ================= -->
    <div id="register-section" <?= !$edit_product ? 'style="display:none;"' : '' ?>>
        <h3><?= $edit_product ? "Editar Producto" : "Registrar Producto" ?></h3>
        <form method="POST" class="mb-4">
            <?php if ($edit_product): ?>
                <input type="hidden" name="id" value="<?= $edit_product['id'] ?>">
            <?php endif; ?>

            <div class="row mb-2">
                <div class="col">
                    <input type="text" name="name" placeholder="Nombre" class="form-control"
                        value="<?= $edit_product['name'] ?? '' ?>" required>
                </div>
                <div class="col">
                    <input type="text" name="type" placeholder="Tipo" class="form-control"
                        value="<?= $edit_product['type'] ?? '' ?>" required>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col">
                    <input type="number" step="0.01" name="price" placeholder="Precio" class="form-control"
                        value="<?= $edit_product['price'] ?? '' ?>" required>
                </div>
                <div class="col">
                    <input type="number" name="stock" placeholder="Stock" class="form-control"
                        value="<?= $edit_product['stock'] ?? '' ?>" required>
                </div>
            </div>

            <input type="text" name="description" placeholder="Descripción" class="form-control mb-2"
                value="<?= $edit_product['description'] ?? '' ?>">

            <button type="submit" 
                name="<?= $edit_product ? 'edit_product' : 'add_product' ?>" 
                class="btn <?= $edit_product ? 'btn-warning' : 'btn-success' ?>">
                <?= $edit_product ? 'Guardar Cambios' : 'Agregar Producto' ?>
            </button>

            <?php if ($edit_product): ?>
                <a href="index.php" class="btn btn-secondary ms-2">Cancelar</a>
            <?php endif; ?>
        </form>
    </div>

<?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
// Tabs
$('#tab-view').click(function(){
    $(this).addClass('active');
    $('#tab-register').removeClass('active');
    $('#view-section').show();
    $('#register-section').hide();
});
$('#tab-register').click(function(){
    $(this).addClass('active');
    $('#tab-view').removeClass('active');
    $('#register-section').show();
    $('#view-section').hide();
});

// Login / registro
$('#btn-login').click(function(){
    $(this).addClass('active'); $('#btn-register').removeClass('active');
    $('#login-form').show(); $('#register-form').hide();
});
$('#btn-register').click(function(){
    $(this).addClass('active'); $('#btn-login').removeClass('active');
    $('#register-form').show(); $('#login-form').hide();
});
</script>
</body>
</html>
