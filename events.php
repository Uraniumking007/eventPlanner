<?php
$page_title = 'Events';
require_once 'includes/header.php';
?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-lg-8">
            <h1 class="display-4 fw-bold">Events</h1>
            <p class="lead text-muted">Discover and join amazing events</p>
        </div>
        <div class="col-lg-4">
            <div class="input-group">
                <input type="text" class="form-control" id="searchEvents" placeholder="Search events...">
                <button class="btn btn-primary" type="button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Event Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="btn-group" role="group">
                <input type="radio" class="btn-check" name="eventFilter" id="allEvents" checked>
                <label class="btn btn-outline-primary" for="allEvents">All Events</label>
                
                <input type="radio" class="btn-check" name="eventFilter" id="upcomingEvents">
                <label class="btn btn-outline-primary" for="upcomingEvents">Upcoming</label>
                
                <input type="radio" class="btn-check" name="eventFilter" id="pastEvents">
                <label class="btn btn-outline-primary" for="pastEvents">Past</label>
            </div>
        </div>
    </div>

    <!-- Events Grid -->
    <div class="row g-4" id="eventsContainer">
        <!-- Sample Event Cards -->
        <div class="col-md-6 col-lg-4">
            <div class="card event-card h-100 shadow-sm">
                <div class="position-relative">
                    <img src="https://via.placeholder.com/400x250/0d6efd/ffffff?text=Tech+Conference" 
                         class="card-img-top" alt="Tech Conference">
                    <div class="event-date position-absolute top-0 start-0 m-3">
                        <div class="small">DEC</div>
                        <div class="fw-bold">15</div>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Tech Conference 2024</h5>
                    <p class="card-text text-muted">
                        <i class="fas fa-map-marker-alt me-2"></i>San Francisco, CA
                    </p>
                    <p class="card-text">Join us for the biggest tech conference of the year featuring industry leaders and innovative solutions.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-primary">Technology</span>
                        <a href="#" class="btn btn-outline-primary btn-sm">Learn More</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card event-card h-100 shadow-sm">
                <div class="position-relative">
                    <img src="https://via.placeholder.com/400x250/198754/ffffff?text=Music+Festival" 
                         class="card-img-top" alt="Music Festival">
                    <div class="event-date position-absolute top-0 start-0 m-3">
                        <div class="small">JAN</div>
                        <div class="fw-bold">20</div>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Summer Music Festival</h5>
                    <p class="card-text text-muted">
                        <i class="fas fa-map-marker-alt me-2"></i>Los Angeles, CA
                    </p>
                    <p class="card-text">Experience the best live music performances in an unforgettable outdoor setting.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-success">Music</span>
                        <a href="#" class="btn btn-outline-primary btn-sm">Learn More</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card event-card h-100 shadow-sm">
                <div class="position-relative">
                    <img src="https://via.placeholder.com/400x250/dc3545/ffffff?text=Food+Expo" 
                         class="card-img-top" alt="Food Expo">
                    <div class="event-date position-absolute top-0 start-0 m-3">
                        <div class="small">FEB</div>
                        <div class="fw-bold">10</div>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title">International Food Expo</h5>
                    <p class="card-text text-muted">
                        <i class="fas fa-map-marker-alt me-2"></i>New York, NY
                    </p>
                    <p class="card-text">Taste cuisines from around the world and meet renowned chefs and food enthusiasts.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-danger">Food</span>
                        <a href="#" class="btn btn-outline-primary btn-sm">Learn More</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card event-card h-100 shadow-sm">
                <div class="position-relative">
                    <img src="https://via.placeholder.com/400x250/ffc107/000000?text=Startup+Meetup" 
                         class="card-img-top" alt="Startup Meetup">
                    <div class="event-date position-absolute top-0 start-0 m-3">
                        <div class="small">MAR</div>
                        <div class="fw-bold">05</div>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Startup Networking Meetup</h5>
                    <p class="card-text text-muted">
                        <i class="fas fa-map-marker-alt me-2"></i>Austin, TX
                    </p>
                    <p class="card-text">Connect with fellow entrepreneurs, investors, and startup enthusiasts in a casual setting.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-warning text-dark">Business</span>
                        <a href="#" class="btn btn-outline-primary btn-sm">Learn More</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card event-card h-100 shadow-sm">
                <div class="position-relative">
                    <img src="https://via.placeholder.com/400x250/0dcaf0/000000?text=Art+Exhibition" 
                         class="card-img-top" alt="Art Exhibition">
                    <div class="event-date position-absolute top-0 start-0 m-3">
                        <div class="small">APR</div>
                        <div class="fw-bold">12</div>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Contemporary Art Exhibition</h5>
                    <p class="card-text text-muted">
                        <i class="fas fa-map-marker-alt me-2"></i>Chicago, IL
                    </p>
                    <p class="card-text">Explore cutting-edge contemporary art from emerging and established artists.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-info">Art</span>
                        <a href="#" class="btn btn-outline-primary btn-sm">Learn More</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card event-card h-100 shadow-sm">
                <div class="position-relative">
                    <img src="https://via.placeholder.com/400x250/6c757d/ffffff?text=Fitness+Workshop" 
                         class="card-img-top" alt="Fitness Workshop">
                    <div class="event-date position-absolute top-0 start-0 m-3">
                        <div class="small">MAY</div>
                        <div class="fw-bold">18</div>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Fitness & Wellness Workshop</h5>
                    <p class="card-text text-muted">
                        <i class="fas fa-map-marker-alt me-2"></i>Miami, FL
                    </p>
                    <p class="card-text">Learn about fitness, nutrition, and wellness from certified professionals.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-secondary">Health</span>
                        <a href="#" class="btn btn-outline-primary btn-sm">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Load More Button -->
    <div class="row mt-5">
        <div class="col-12 text-center">
            <button class="btn btn-primary btn-lg" id="loadMoreEvents">
                <i class="fas fa-plus me-2"></i>Load More Events
            </button>
        </div>
    </div>
</div>

<?php
$page_scripts = "
// Event filter functionality
$('input[name=\"eventFilter\"]').on('change', function() {
    var filter = $(this).attr('id');
    console.log('Filter changed to: ' + filter);
    // Add filter logic here
});

// Load more events
$('#loadMoreEvents').on('click', function() {
    var btn = $(this);
    var originalText = btn.html();
    
    btn.prop('disabled', true).html('<span class=\"loading\"></span> Loading...');
    
    // Simulate loading more events
    setTimeout(function() {
        btn.prop('disabled', false).html(originalText);
        showAlert('info', 'More events loaded!');
    }, 2000);
});
";

require_once 'includes/footer.php';
?>
