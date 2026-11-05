<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (
        !isset($_FILES['xml_file']) ||
        $_FILES['xml_file']['error'] !== UPLOAD_ERR_OK
    ) {
        $error = 'Please select a valid XML file.';
    } else {

        $file = $_FILES['xml_file']['tmp_name'];

        libxml_use_internal_errors(true);
        $xml = simplexml_load_file($file);

        if ($xml === false) {
            $error = 'Invalid XML file.';
        } else {

            try {
                $pdo->beginTransaction();

                $customerCount = 0;
                $serviceCount = 0;
                $orderCount = 0;

                /* =========================
                   IMPORT CUSTOMERS
                ========================= */

                if (isset($xml->customers->customer)) {

                    $insertCustomer = $pdo->prepare("
                        INSERT INTO customers (name, phone, email)
                        VALUES (:name, :phone, :email)
                    ");

                    foreach ($xml->customers->customer as $customer) {

                        $name = trim((string)$customer->name);
                        $phone = trim((string)$customer->phone);
                        $email = trim((string)$customer->email);

                        if ($name === '') {
                            continue;
                        }

                        /* Check duplicate using phone */
                        $check = $pdo->prepare("
                            SELECT id
                            FROM customers
                            WHERE phone = :phone
                            LIMIT 1
                        ");

                        $check->execute([
                            'phone' => $phone
                        ]);

                        if (!$check->fetch()) {

                            $insertCustomer->execute([
                                'name' => $name,
                                'phone' => $phone,
                                'email' => $email
                            ]);

                            $customerCount++;
                        }
                    }
                }


                /* =========================
                   IMPORT SERVICES
                ========================= */

                if (isset($xml->services->service)) {

                    $insertService = $pdo->prepare("
                        INSERT INTO services
                        (
                            service_name,
                            description,
                            price_per_kg,
                            is_active
                        )
                        VALUES
                        (
                            :service_name,
                            :description,
                            :price_per_kg,
                            :is_active
                        )
                    ");

                    foreach ($xml->services->service as $service) {

                        $serviceName =
                            trim((string)$service->serviceName);

                        $description =
                            trim((string)$service->description);

                        $price =
                            (float)$service->pricePerKg;

                        if ($serviceName === '') {
                            continue;
                        }

                        /* Check duplicate service */
                        $check = $pdo->prepare("
                            SELECT id
                            FROM services
                            WHERE service_name = :service_name
                            LIMIT 1
                        ");

                        $check->execute([
                            'service_name' => $serviceName
                        ]);

                        if (!$check->fetch()) {

                            $activeValue =
                                strtolower(
                                    trim((string)$service->active)
                                );

                            $active =
                                in_array(
                                    $activeValue,
                                    ['1', 'true', 'yes'],
                                    true
                                );

                            $insertService->execute([
                                'service_name' =>
                                    $serviceName,

                                'description' =>
                                    $description,

                                'price_per_kg' =>
                                    $price,

                                'is_active' =>
                                    $active
                            ]);

                            $serviceCount++;
                        }
                    }
                }


                /* =========================
                   IMPORT ORDERS
                ========================= */

                if (isset($xml->orders->order)) {

                    foreach ($xml->orders->order as $order) {

                        $customerName =
                            trim((string)$order->customerName);

                        $serviceName =
                            trim((string)$order->serviceName);

                        $weight =
                            (float)$order->weightKg;

                        $dueDate =
                            trim((string)$order->dueDate);

                        $status =
                            trim((string)$order->status);


                        /* Find customer */

                        $customerStmt = $pdo->prepare("
                            SELECT id
                            FROM customers
                            WHERE name = :name
                            ORDER BY id
                            LIMIT 1
                        ");

                        $customerStmt->execute([
                            'name' => $customerName
                        ]);

                        $customer =
                            $customerStmt->fetch();

                        if (!$customer) {
                            continue;
                        }


                        /* Find service */

                        $serviceStmt = $pdo->prepare("
                            SELECT id, price_per_kg
                            FROM services
                            WHERE service_name = :service_name
                            LIMIT 1
                        ");

                        $serviceStmt->execute([
                            'service_name' =>
                                $serviceName
                        ]);

                        $service =
                            $serviceStmt->fetch();

                        if (!$service) {
                            continue;
                        }


                        /* Calculate total */

                        $totalAmount =
                            $weight *
                            (float)$service['price_per_kg'];


                        /* Validate status */

                        $allowedStatuses = [
                            'Received',
                            'Washing',
                            'Ready',
                            'Delivered'
                        ];

                        if (
                            !in_array(
                                $status,
                                $allowedStatuses,
                                true
                            )
                        ) {
                            $status = 'Received';
                        }


                        /* Default date */

                        if ($dueDate === '') {
                            $dueDate = date('Y-m-d');
                        }


                        /* Insert order */

                        $insertOrder = $pdo->prepare("
                            INSERT INTO orders
                            (
                                customer_id,
                                service_id,
                                weight,
                                due_date,
                                total_amount,
                                status
                            )
                            VALUES
                            (
                                :customer_id,
                                :service_id,
                                :weight,
                                :due_date,
                                :total_amount,
                                :status
                            )
                        ");

                        $insertOrder->execute([
                            'customer_id' =>
                                $customer['id'],

                            'service_id' =>
                                $service['id'],

                            'weight' =>
                                $weight,

                            'due_date' =>
                                $dueDate,

                            'total_amount' =>
                                $totalAmount,

                            'status' =>
                                $status
                        ]);

                        $orderCount++;
                    }
                }


                $pdo->commit();

                $message =
                    "XML imported successfully. "
                    . $customerCount . " customers, "
                    . $serviceCount . " services and "
                    . $orderCount . " orders imported.";

            } catch (Exception $e) {

                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }

                $error =
                    "Import failed: " .
                    $e->getMessage();
            }
        }
    }
}
?>

<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="page-header">
    <div>
        <h1>Import XML</h1>
        <p>
            Load customers, services and orders
            from an XML file into PostgreSQL.
        </p>
    </div>
</div>


<?php if ($message): ?>

    <div class="alert alert-success">
        <?= htmlspecialchars($message) ?>
    </div>

<?php endif; ?>


<?php if ($error): ?>

    <div class="alert alert-danger">
        <?= htmlspecialchars($error) ?>
    </div>

<?php endif; ?>


<div class="card">

    <h2>Upload LaundryCare XML</h2>

    <form
        method="POST"
        enctype="multipart/form-data"
    >

        <div class="form-group">

            <label for="xml_file">
                Select XML file
            </label>

            <input
                type="file"
                name="xml_file"
                id="xml_file"
                accept=".xml,application/xml,text/xml"
                required
            >

        </div>

        <button
            type="submit"
            class="btn btn-primary"
        >
            Import XML
        </button>

    </form>

</div>


<div class="card">

    <h3>Import process</h3>

    <p>
        The XML file is read by PHP and the data
        is inserted into the PostgreSQL database.
    </p>

    <p>
        Existing customers and services are skipped
        to reduce duplicate records.
    </p>

    <p>
        Imported orders are linked to the corresponding
        customer and service.
    </p>

</div>


<?php require_once __DIR__ . '/../includes/footer.php'; ?>