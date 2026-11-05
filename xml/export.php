<?php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

/*
 * Convert normal text into safe XML text
 */
function xmlSafe($value)
{
    return htmlspecialchars(
        (string)$value,
        ENT_XML1 | ENT_QUOTES,
        'UTF-8'
    );
}

$xml = new SimpleXMLElement(
    '<?xml version="1.0" encoding="UTF-8"?><laundryData/>'
);


/* =========================
   CUSTOMERS
========================= */

$customers = $xml->addChild('customers');

$stmt = $pdo->query("
    SELECT id, name, phone, email
    FROM customers
    ORDER BY id
");

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    $customer = $customers->addChild('customer');

    $customer->addChild('customerId', $row['id']);
    $customer->addChild('name', xmlSafe($row['name']));
    $customer->addChild('phone', xmlSafe($row['phone']));
    $customer->addChild('email', xmlSafe($row['email']));
}


/* =========================
   SERVICES
========================= */

$services = $xml->addChild('services');

$stmt = $pdo->query("
    SELECT id, service_name, description, price_per_kg, is_active
    FROM services
    ORDER BY id
");

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    $service = $services->addChild('service');

    $service->addChild('serviceId', $row['id']);
    $service->addChild(
        'serviceName',
        xmlSafe($row['service_name'])
    );
    $service->addChild(
        'description',
        xmlSafe($row['description'])
    );
    $service->addChild(
        'pricePerKg',
        $row['price_per_kg']
    );
    $service->addChild(
        'active',
        $row['is_active'] ? 'true' : 'false'
    );
}


/* =========================
   ORDERS
========================= */

$orders = $xml->addChild('orders');

$stmt = $pdo->query("
    SELECT
        o.id,
        c.name AS customer_name,
        s.service_name,
        o.weight,
        o.total_amount,
        o.due_date,
        o.status
    FROM orders o
    JOIN customers c ON o.customer_id = c.id
    JOIN services s ON o.service_id = s.id
    ORDER BY o.id
");

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    $order = $orders->addChild('order');

    $order->addChild('orderId', $row['id']);

    $order->addChild(
        'customerName',
        xmlSafe($row['customer_name'])
    );

    $order->addChild(
        'serviceName',
        xmlSafe($row['service_name'])
    );

    $order->addChild(
        'weightKg',
        $row['weight']
    );

    $order->addChild(
        'totalAmount',
        $row['total_amount']
    );

    $order->addChild(
        'dueDate',
        $row['due_date']
    );

    $order->addChild(
        'status',
        xmlSafe($row['status'])
    );
}


/* =========================
   SEND XML FILE
========================= */

header('Content-Type: application/xml; charset=utf-8');
header(
    'Content-Disposition: attachment; filename="laundrycare_export.xml"'
);

echo $xml->asXML();

exit;