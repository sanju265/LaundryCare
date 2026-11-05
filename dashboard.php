<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/database.php';

$title = 'Overview';

$customers = (int)$pdo->query('SELECT COUNT(*) FROM customers')->fetchColumn();

$services = (int)$pdo->query('SELECT COUNT(*) FROM services')->fetchColumn();

$orders = (int)$pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();

$progress = (int)$pdo->query("
    SELECT COUNT(*)
    FROM orders
    WHERE status <> 'Delivered'
")->fetchColumn();


// =========================
// RECENT ORDERS
// =========================

$recent = $pdo->query("
    SELECT
        o.id,
        c.name AS customer,
        s.service_name,
        o.due_date,
        o.total_amount,
        o.status
    FROM orders o
    JOIN customers c ON c.id = o.customer_id
    JOIN services s ON s.id = o.service_id
    ORDER BY o.id DESC
    LIMIT 8
")->fetchAll();


// =========================
// TODAY'S DEADLINE ORDERS
// =========================

$todayOrders = $pdo->query("
    SELECT
        o.id,
        c.name AS customer,
        s.service_name,
        o.due_date,
        o.total_amount,
        o.status
    FROM orders o
    JOIN customers c ON c.id = o.customer_id
    JOIN services s ON s.id = o.service_id
    WHERE o.due_date = CURRENT_DATE
    ORDER BY o.id DESC
")->fetchAll();


function statusClass($s)
{
    return strtolower($s);
}

include __DIR__ . '/includes/header.php';
?>

<div class="page-head hero">

  <div>

    <div class="eyebrow">
        <?= date('l, d F Y') ?>
    </div>

    <h1>Overview</h1>

    <div class="muted">
        Here's what's happening at your laundry today.
    </div>

  </div>


  <div class="actions">

    <a class="btn" href="/laundrycare/orders/index.php">
        + Orders
    </a>

    <a class="btn secondary" href="/laundrycare/customers/index.php">
        Customers
    </a>

    <a class="btn secondary" href="/laundrycare/xml/export.php">
        Export XML
    </a>

  </div>

</div>


<!-- =========================
     STATISTICS
========================= -->

<div class="grid">

  <div class="card stat-card blue">

    <div class="stat-icon">
        🧺
    </div>

    <div>
        <div class="muted">
            Total Orders
        </div>

        <div class="stat">
            <?= $orders ?>
        </div>
    </div>

  </div>


  <div class="card stat-card amber">

    <div class="stat-icon">
        ⏳
    </div>

    <div>
        <div class="muted">
            In Progress
        </div>

        <div class="stat">
            <?= $progress ?>
        </div>
    </div>

  </div>


  <div class="card stat-card green">

    <div class="stat-icon">
        👥
    </div>

    <div>
        <div class="muted">
            Customers
        </div>

        <div class="stat">
            <?= $customers ?>
        </div>
    </div>

  </div>


  <div class="card stat-card purple">

    <div class="stat-icon">
        ✨
    </div>

    <div>
        <div class="muted">
            Services
        </div>

        <div class="stat">
            <?= $services ?>
        </div>
    </div>

  </div>

</div>



<!-- =========================
     RECENT ORDERS
========================= -->

<div class="card" style="margin-top:20px">

  <div class="card-head">

    <h2>
        Recent Orders
    </h2>

    <a class="link" href="/laundrycare/orders/index.php">
        View all &rarr;
    </a>

  </div>


  <div class="table-wrap">

    <table class="table">

      <tr>
        <th>ID</th>
        <th>Customer</th>
        <th>Service</th>
        <th>Due Date</th>
        <th>Total</th>
        <th>Status</th>
      </tr>


      <?php foreach($recent as $r): ?>

      <tr>

        <td class="id-cell">
            <?= $r['id'] ?>
        </td>

        <td>
            <strong>
                <?= htmlspecialchars($r['customer']) ?>
            </strong>
        </td>

        <td>
            <?= htmlspecialchars($r['service_name']) ?>
        </td>

        <td>
            <?= htmlspecialchars($r['due_date']) ?>
        </td>

        <td class="money">
            ₹<?= number_format($r['total_amount'], 2) ?>
        </td>

        <td>

          <span class="badge <?= statusClass($r['status']) ?>">
              <?= htmlspecialchars($r['status']) ?>
          </span>

        </td>

      </tr>

      <?php endforeach; ?>


      <?php if(!$recent): ?>

      <tr>

        <td colspan="6" class="empty">
            No orders yet.
        </td>

      </tr>

      <?php endif; ?>

    </table>

  </div>

</div>



<!-- =========================
     TODAY'S DEADLINE ORDERS
========================= -->

<div class="card" style="margin-top:20px">

  <div class="card-head">

    <h2>
        Today's Deadline Orders
    </h2>

    <a class="link" href="/laundrycare/orders/index.php">
        View all &rarr;
    </a>

  </div>


  <div class="table-wrap">

    <table class="table">

      <tr>
        <th>ID</th>
        <th>Customer</th>
        <th>Service</th>
        <th>Due Date</th>
        <th>Total</th>
        <th>Status</th>
      </tr>


      <?php foreach($todayOrders as $r): ?>

      <tr>

        <td class="id-cell">
            <?= $r['id'] ?>
        </td>

        <td>
            <strong>
                <?= htmlspecialchars($r['customer']) ?>
            </strong>
        </td>

        <td>
            <?= htmlspecialchars($r['service_name']) ?>
        </td>

        <td>
            <strong>
                <?= htmlspecialchars($r['due_date']) ?>
            </strong>
        </td>

        <td class="money">
            ₹<?= number_format($r['total_amount'], 2) ?>
        </td>

        <td>

          <span class="badge <?= statusClass($r['status']) ?>">
              <?= htmlspecialchars($r['status']) ?>
          </span>

        </td>

      </tr>

      <?php endforeach; ?>


      <?php if(!$todayOrders): ?>

      <tr>

        <td colspan="6" class="empty">
            🎉 No orders are due today.
        </td>

      </tr>

      <?php endif; ?>

    </table>

  </div>

</div>


<?php include __DIR__ . '/includes/footer.php'; ?>