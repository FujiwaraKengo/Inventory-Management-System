<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="home.php">Item Inventory</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <?php if (isset($_SESSION['$verify_user_id'])): ?>
        <li class="nav-item">
          <a class="nav-link active" href="home.php">Record Log</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="index.php">Stock Items</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="user-list.php">User List</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="supplier.php">Supplier Contacts</a>
        </li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav ms-auto">
        <?php if (!isset($_SESSION['$verify_user_id'])): ?>
        <li class="nav-item">
          <a class="nav-link active" href="login.php">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="register.php">Register</a>
        </li>
        <?php else: ?>
        <li class="nav-item">
          <a class="nav-link active" href="logout.php">Log Out</a>
        </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
