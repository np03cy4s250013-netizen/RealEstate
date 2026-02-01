<?php
require_once __DIR__ . '/../includes/functions.php';

$location   = $_GET['location']   ?? '';
$min_price  = $_GET['min_price']  ?? '';
$max_price  = $_GET['max_price']  ?? '';
$house_type = $_GET['house_type'] ?? '';

$query  = "SELECT * FROM properties WHERE 1=1";
$params = [];

if ($location !== '') {
    $query .= " AND location LIKE :location";
    $params[':location'] = '%' . $location . '%';
}
if ($min_price !== '') {
    $query .= " AND price >= :min_price";
    $params[':min_price'] = $min_price;
}
if ($max_price !== '') {
    $query .= " AND price <= :max_price";
    $params[':max_price'] = $max_price;
}
if ($house_type !== '') {
    $query .= " AND house_type = :house_type";
    $params[':house_type'] = $house_type;
}

$query .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$properties = $stmt->fetchAll();

if (!$properties): ?>
    <p class="property-list__empty">
        No properties found for the selected criteria.
    </p>
<?php else: ?>
    <?php foreach ($properties as $property): ?>
        <article class="property-card">
            <header class="property-card__header">
                <h3 class="property-card__title">
                    <?php echo h($property['title']); ?>
                </h3>
            </header>

            <div class="property-card__content">
                <?php if ($property['image_path']): ?>
                    <figure class="property-card__media">
                        <img
                            src="../<?php echo h($property['image_path']); ?>"
                            alt="Property in <?php echo h($property['location']); ?>"
                            class="property-card__image">
                    </figure>
                <?php endif; ?>

                <section class="property-card__details" aria-label="Property details">
                    <p class="property-card__detail">
                        <span class="property-card__label">Location:</span>
                        <span class="property-card__value">
                            <?php echo h($property['location']); ?>
                        </span>
                    </p>
                    <p class="property-card__detail">
                        <span class="property-card__label">Type:</span>
                        <span class="property-card__value">
                            <?php echo h($property['house_type']); ?>
                        </span>
                    </p>
                    <p class="property-card__detail">
                        <span class="property-card__label">Price:</span>
                        <span class="property-card__value">
                            <?php echo h($property['price']); ?>
                        </span>
                    </p>
                </section>

                <section class="property-card__description" aria-label="Description">
                    <p>
                        <?php echo nl2br(h($property['description'])); ?>
                    </p>
                </section>
            </div>

            <footer class="property-card__footer">
                <a
                    href="edit.php?id=<?php echo (int)$property['id']; ?>"
                    class="button button--small">
                    Edit
                </a>
                <a
                    href="delete.php?id=<?php echo (int)$property['id']; ?>"
                    class="button button--small button--danger"
                    onclick="return confirm('Delete this property?');">
                    Delete
                </a>
            </footer>
        </article>
    <?php endforeach; ?>
<?php endif; ?>
