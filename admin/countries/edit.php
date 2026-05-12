<?php
session_start();
require_once __DIR__ . '/../../src/Core/Session.php';
require_once __DIR__ . '/../../src/Validation/Validator.php';

$session = new Session();

if (!$session->isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../src/Models/Country.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: index.php');
    exit;
}

try {
    $db = (new Database())->getConnection();
    $countryModel = new Country($db);
    $country = $countryModel->getById($id);
    
    if (!$country) {
        header('Location: index.php');
        exit;
    }
} catch (Exception $e) {
    error_log("Error loading country for edit: " . $e->getMessage());
    header('Location: index.php');
    exit;
}

$continents = ['Africa', 'Asia', 'Europe', 'North America', 'South America', 'Oceania', 'Antarctica'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';
    
    if (!$session->validateCsrfToken($csrfToken)) {
        $errors[] = 'Invalid security token. Please try again.';
    } else {
        $validator = new Validator($_POST);
        $validator->required('name', 'Country name is required')
                  ->minLength('name', 2, 'Country name must be at least 2 characters')
                  ->required('capital', 'Capital is required')
                  ->required('continent', 'Continent is required')
                  ->required('population', 'Population is required')
                  ->numeric('population', 'Population must be a valid positive number')
                  ->url('image_url', 'Please enter a valid URL for the image');
        
        if ($validator->passes()) {
            try {
                $data = [
                    'name' => $_POST['name'],
                    'capital' => $_POST['capital'],
                    'continent' => $_POST['continent'],
                    'population' => (int)$_POST['population'],
                    'language' => $_POST['language'] ?? '',
                    'currency' => $_POST['currency'] ?? '',
                    'description' => $_POST['description'] ?? '',
                    'image_url' => $_POST['image_url'] ?? '',
                    'best_season' => $_POST['best_season'] ?? ''
                ];
                
                if ($countryModel->update($id, $data)) {
                    $session->set('success', 'Country updated successfully!');
                    header('Location: index.php');
                    exit;
                } else {
                    $errors[] = 'Failed to update country. Please try again.';
                }
            } catch (Exception $e) {
                error_log("Error updating country: " . $e->getMessage());
                $errors[] = 'An error occurred. Please try again.';
            }
        } else {
            $errors = $validator->getErrors();
        }
    }
    
    $country = array_merge($country, $_POST);
}
?>

<?php include '../../includes/header.php'; ?>

<div class="form-container">
    <h1>Edit Country: <?php echo htmlspecialchars($country['name'], ENT_QUOTES, 'UTF-8'); ?></h1>
    
    <?php if (!empty($errors)): ?>
        <div class="alert alert-error" role="alert">
            <strong>Please fix the following errors:</strong>
            <ul>
                <?php foreach ($errors as $field => $error): ?>
                    <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <form method="POST" novalidate>
        <input type="hidden" name="csrf_token" value="<?php echo $session->generateCsrfToken(); ?>">
        
        <div class="form-row">
            <div class="form-group">
                <label for="name">Country Name *</label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="<?php echo htmlspecialchars($country['name'], ENT_QUOTES, 'UTF-8'); ?>" 
                       required 
                       minlength="2"
                       aria-required="true">
            </div>
            <div class="form-group">
                <label for="capital">Capital *</label>
                <input type="text" 
                       id="capital" 
                       name="capital" 
                       value="<?php echo htmlspecialchars($country['capital'], ENT_QUOTES, 'UTF-8'); ?>" 
                       required
                       aria-required="true">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="continent">Continent *</label>
                <select id="continent" name="continent" required aria-required="true">
                    <option value="">Select Continent</option>
                    <?php foreach ($continents as $continent): ?>
                        <option value="<?php echo $continent; ?>" 
                                <?php echo ($country['continent'] ?? '') === $continent ? 'selected' : ''; ?>>
                            <?php echo $continent; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="population">Population *</label>
                <input type="number" 
                       id="population" 
                       name="population" 
                       value="<?php echo htmlspecialchars($country['population'], ENT_QUOTES, 'UTF-8'); ?>" 
                       required 
                       min="1"
                       aria-required="true">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="language">Language</label>
                <input type="text" 
                       id="language" 
                       name="language" 
                       value="<?php echo htmlspecialchars($country['language'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="form-group">
                <label for="currency">Currency</label>
                <input type="text" 
                       id="currency" 
                       name="currency" 
                       value="<?php echo htmlspecialchars($country['currency'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>
        </div>
        
        <div class="form-group">
            <label for="best_season">Best Season to Visit</label>
            <input type="text" 
                   id="best_season" 
                   name="best_season" 
                   value="<?php echo htmlspecialchars($country['best_season'], ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        
        <div class="form-group">
            <label for="image_url">Image URL</label>
            <input type="url" 
                   id="image_url" 
                   name="image_url" 
                   value="<?php echo htmlspecialchars($country['image_url'], ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" 
                      name="description" 
                      rows="6"><?php echo htmlspecialchars($country['description'], ENT_QUOTES, 'UTF-8'); ?></textarea>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Country</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>