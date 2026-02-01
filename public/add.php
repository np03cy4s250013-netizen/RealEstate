<?php
require_once __DIR__ . '/../includes/functions.php';

$errors      = [];
$title       = '';
$description = '';
$location    = '';
$price       = '';
$house_type  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = $_POST['title']       ?? '';
    $description = $_POST['description'] ?? '';
    $location    = $_POST['location']    ?? '';
    $price       = $_POST['price']       ?? '';
    $house_type  = $_POST['house_type']  ?? '';

    $data = [
        'title'       => $title,
        'description' => $description,
        'location'    => $location,
        'price'       => $price,
        'house_type'  => $house_type,
    ];

    $errors = validatePropertyData($data);

    $imagePath = handleImageUpload('image');

    if (empty($errors)) {
        $sql = "INSERT INTO properties
                (title, description, location, price, house_type, image_path)
                VALUES
                (:title, :description, :location, :price, :house_type, :image_path)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title'       => $title,
            ':description' => $description,
            ':location'    => $location,
            ':price'       => $price,
            ':house_type'  => $house_type,
            ':image_path'  => $imagePath
        ]);

        header('Location: index.php');
        exit;
    }
}
?>
<?php
include __DIR__ . '/../includes/header.php';
?>

<section class="page" aria-labelledby="page-title">
    <header class="page__header">
        <h2 id="page-title" class="page__title">Add property</h2>
        <p class="page__subtitle">
            Create a new property listing for real estate agents.
        </p>
    </header>

    <?php if ($errors): ?>
        <section class="page__section" aria-label="Form errors">
            <ul class="notification notification--error">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo h($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>

    <section class="page__section" aria-label="Add property form">
        <form
            class="property-form"
            method="post"
            enctype="multipart/form-data">
            <fieldset class="property-form__fieldset">
                <legend class="property-form__legend">Property information</legend>

                <div class="property-form__group">
                    <label for="title" class="property-form__label">Title</label>
                    <input
                        type="text"
                        name="title"
                        id="title"
                        class="property-form__input"
                        required
                        value="<?php echo h($title); ?>">
                </div>

                <div class="property-form__group">
                    <label for="description" class="property-form__label">Description</label>
                    <textarea
                        name="description"
                        id="description"
                        class="property-form__textarea"
                        rows="4"
                        required><?php echo h($description); ?></textarea>
                </div>

                <div class="property-form__group">
                    <label for="location" class="property-form__label">Location</label>
                    <input
                        type="text"
                        name="location"
                        id="location"
                        class="property-form__input"
                        required
                        value="<?php echo h($location); ?>">
                </div>

                <div class="property-form__group">
                    <label for="price" class="property-form__label">Price</label>
                    <input
                        type="number"
                        name="price"
                        id="price"
                        class="property-form__input"
                        min="0"
                        step="0.01"
                        required
                        value="<?php echo h($price); ?>">
                </div>

                <div class="property-form__group">
                    <label for="house_type" class="property-form__label">House type</label>
                    <select
                        name="house_type"
                        id="house_type"
                        class="property-form__select"
                        required>
                        <option value="">Select type</option>
                        <?php foreach (getHouseTypes() as $type): ?>
                            <option
                                value="<?php echo h($type); ?>"
                                <?php if ($house_type === $type) echo 'selected'; ?>>
                                <?php echo h($type); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="property-form__group">
                    <label for="image" class="property-form__label">Property image</label>
                    <input
                        type="file"
                        name="image"
                        id="image"
                        class="property-form__input"
                        accept="image/*">
                    <p class="property-form__hint">
                        Optional: JPG or PNG, max 2MB.
                    </p>
                </div>
            </fieldset>

            <div class="property-form__actions">
                <button type="submit" class="button button--primary">
                    Save property
                </button>
                <a href="index.php" class="button button--secondary">
                    Cancel
                </a>
            </div>
        </form>
    </section>
</section>

<?php
include __DIR__ . '/../includes/footer.php';
?>
