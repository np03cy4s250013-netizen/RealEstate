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
?>
<?php include __DIR__ . '/../includes/header.php'; ?>

<section class="page" aria-labelledby="page-title">
    <header class="page__header">
        <h2 id="page-title" class="page__title">Property listings</h2>
        <p class="page__subtitle">
            Browse, filter, and manage properties in the system.
        </p>
    </header>

    <section class="page__section" aria-label="Filter properties">
        <form id="searchForm" class="filter-form" method="get">
            <fieldset class="filter-form__fieldset">
                <legend class="filter-form__legend">Filter properties</legend>

                <div class="filter-form__grid">
                    <div class="filter-form__group">
                        <label for="location" class="filter-form__label">Location</label>
                        <input
                            type="text"
                            name="location"
                            id="location"
                            class="filter-form__input"
                            value="<?php echo h($location); ?>"
                            placeholder="e.g. Kathmandu">
                    </div>

                    <div class="filter-form__group">
                        <label for="min_price" class="filter-form__label">Minimum price</label>
                        <input
                            type="number"
                            name="min_price"
                            id="min_price"
                            class="filter-form__input"
                            min="0"
                            step="0.01"
                            value="<?php echo h($min_price); ?>">
                    </div>

                    <div class="filter-form__group">
                        <label for="max_price" class="filter-form__label">Maximum price</label>
                        <input
                            type="number"
                            name="max_price"
                            id="max_price"
                            class="filter-form__input"
                            min="0"
                            step="0.01"
                            value="<?php echo h($max_price); ?>">
                    </div>

                    <div class="filter-form__group">
                        <label for="house_type" class="filter-form__label">House type</label>
                        <select
                            name="house_type"
                            id="house_type"
                            class="filter-form__select">
                            <option value="">Any type</option>
                            <?php foreach (getHouseTypes() as $type): ?>
                                <option
                                    value="<?php echo h($type); ?>"
                                    <?php if ($house_type === $type) echo 'selected'; ?>>
                                    <?php echo h($type); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="filter-form__actions">
                    <button type="submit" class="button button--primary">
                        Apply filters
                    </button>
                    <a href="index.php" class="button button--secondary">
                        Reset
                    </a>
                </div>
            </fieldset>
        </form>
    </section>

    <section
        id="propertiesContainer"
        class="page__section property-list"
        aria-label="Property results">
        <?php if (!$properties): ?>
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
    </section>
</section>

<?php
include __DIR__ . '/../includes/footer.php';
?>
