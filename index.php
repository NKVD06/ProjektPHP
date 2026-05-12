<?php
session_start();
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/src/Models/Country.php';

$db = (new Database())->getConnection();
$countryModel = new Country($db);

$searchTerm = $_GET['search'] ?? '';
$selectedContinent = $_GET['continent'] ?? '';

$continents = $countryModel->getContinents();

if ($searchTerm) {
    $countries = $countryModel->search($searchTerm);
} elseif ($selectedContinent) {
    $countries = $countryModel->getByContinent($selectedContinent);
} else {
    $countries = $countryModel->getAll();
}
?>

<?php include 'includes/header.php'; ?>

<section class="hero">
    <h1>Discover the World</h1>
    <p>Explore countries, plan your next adventure</p>
    
    <form method="GET" class="search-form">
        <input type="text" 
               name="search" 
               placeholder="Search countries, capitals..." 
               value="<?php echo htmlspecialchars($searchTerm); ?>">
        <button type="submit">Search</button>
    </form>
</section>

<section class="filters">
    <div class="continent-filters">
        <a href="index.php" class="filter-btn <?php echo !$selectedContinent ? 'active' : ''; ?>">
            All
        </a>
        <?php foreach ($continents as $continent): ?>
            <a href="?continent=<?php echo urlencode($continent); ?>" 
               class="filter-btn <?php echo $selectedContinent === $continent ? 'active' : ''; ?>">
                <?php echo htmlspecialchars($continent); ?>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<section class="countries">
    <div class="country-grid">
        <?php foreach ($countries as $country): ?>
            <div class="country-card">
                <div class="country-image">
                    <?php if ($country['image_url']): ?>
                        <img src="<?php echo htmlspecialchars($country['image_url']); ?>" 
                             alt="<?php echo htmlspecialchars($country['name']); ?>">
                    <?php else: ?>
                        <div class="no-image">No Image</div>
                    <?php endif; ?>
                </div>
                <div class="country-info">
                    <h3><?php echo htmlspecialchars($country['name']); ?></h3>
                    <p class="capital">Capital: <?php echo htmlspecialchars($country['capital']); ?></p>
                    <p class="continent">Continent: <?php echo htmlspecialchars($country['continent']); ?></p>
                    <a href="country.php?id=<?php echo $country['id']; ?>" class="btn-details">View Details</a>
                </div>
            </div>
        <?php endforeach; ?>
        
        <?php if (empty($countries)): ?>
            <div class="no-results">
                <p>No countries found matching your criteria.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>