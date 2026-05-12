<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../src/Models/Country.php';

$id = (int)($_GET['id'] ?? 0);
$db = (new Database())->getConnection();
$countryModel = new Country($db);
$country = $countryModel->getById($id);

if (!$country) {
    header('Location: index.php');
    exit;
}

$continents = ['Africa', 'Asia', 'Europe', 'North America', 'South America', 'Oceania', 'Antarctica'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => trim($_POST['name'] ?? ''),
        'capital' => trim($_POST['capital'] ?? ''),
        'continent' => $_POST['continent'] ?? '',
        'population' => (int)($_POST['population'] ?? 0),
        'language' => trim($_POST['language'] ?? ''),
        'currency' => trim($_POST['currency'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'image_url' => trim($_POST['image_url'] ?? ''),
        'best_season' => trim($_POST['best_season'] ?? '')
    ];
    
    if (empty($data['name'])) {
        $errors[] = 'Country name is required';
    }
    if (empty($data['capital'])) {
        $errors[] = 'Capital is required';
    }
    if (empty($data['continent'])) {
        $errors[] = 'Continent is required';
    }
    if ($data['population'] <= 0) {
        $errors[] = 'Valid population is required';
    }
    
    if (empty($errors)) {
        if ($countryModel->update($id, $data)) {
            header('Location: index.php');
            exit;
        }
        $errors[] = 'Failed to update country';
    }
}
?>

<?php include '../../includes/header.php'; ?>

<div class="form-container">
    <h1>Edit Country</h1>
    
    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="form-row">
            <div class="form-group">
                <label for="name">Country Name *</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? $country['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="capital">Capital *</label>
                <input type="text" id="capital" name="capital" value="<?php echo htmlspecialchars($_POST['capital'] ?? $country['capital']); ?>" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="continent">Continent *</label>
                <select id="continent" name="continent" required>
                    <option value="">Select Continent</option>
                    <?php foreach ($continents as $continent): ?>
                        <option value="<?php echo $continent; ?>" <?php echo ($_POST['continent'] ?? $country['continent']) === $continent ? 'selected' : ''; ?>>
                            <?php echo $continent; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="population">Population *</label>
                <input type="number" id="population" name="population" value="<?php echo $_POST['population'] ?? $country['population']; ?>" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="language">Language</label>
                <input type="text" id="language" name="language" value="<?php echo htmlspecialchars($_POST['language'] ?? $country['language']); ?>">
            </div>
            <div class="form-group">
                <label for="currency">Currency</label>
                <input type="text" id="currency" name="currency" value="<?php echo htmlspecialchars($_POST['currency'] ?? $country['currency']); ?>">
            </div>
        </div>
        
        <div class="form-group">
            <label for="best_season">Best Season to Visit</label>
            <input type="text" id="best_season" name="best_season" value="<?php echo htmlspecialchars($_POST['best_season'] ?? $country['best_season']); ?>">
        </div>
        
        <div class="form-group">
            <label for="image_url">Image URL</label>
            <input type="url" id="image_url" name="image_url" value="<?php echo htmlspecialchars($_POST['image_url'] ?? $country['image_url']); ?>">
        </div>
        
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="6"><?php echo htmlspecialchars($_POST['description'] ?? $country['description']); ?></textarea>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Country</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>