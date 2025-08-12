$(document).ready(function () {
  "use strict";

  // Initialize tooltips
  var tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Initialize popovers
  var popoverTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="popover"]')
  );
  var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl);
  });

  // Smooth scrolling for anchor links
  $('a[href^="#"]').on("click", function (event) {
    var target = $(this.getAttribute("href"));
    if (target.length) {
      event.preventDefault();
      $("html, body")
        .stop()
        .animate(
          {
            scrollTop: target.offset().top - 70,
          },
          1000
        );
    }
  });

  // Navbar scroll effect
  $(window).scroll(function () {
    if ($(this).scrollTop() > 50) {
      $(".navbar").addClass("navbar-scrolled");
    } else {
      $(".navbar").removeClass("navbar-scrolled");
    }
  });

  // Form validation
  $(".needs-validation").on("submit", function (event) {
    if (!this.checkValidity()) {
      event.preventDefault();
      event.stopPropagation();
    }
    $(this).addClass("was-validated");
  });

  // Auto-hide alerts after 5 seconds
  setTimeout(function () {
    $(".alert").fadeOut("slow");
  }, 5000);

  // Event card hover effects
  $(".event-card").hover(
    function () {
      $(this).find(".card-img-overlay").fadeIn(300);
    },
    function () {
      $(this).find(".card-img-overlay").fadeOut(300);
    }
  );

  // Search functionality
  $("#searchEvents").on("keyup", function () {
    var value = $(this).val().toLowerCase();
    $(".event-card").filter(function () {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    });
  });

  // Date picker initialization
  if ($.fn.datepicker) {
    $(".datepicker").datepicker({
      format: "yyyy-mm-dd",
      autoclose: true,
      todayHighlight: true,
    });
  }

  // AJAX form submission
  $(".ajax-form").on("submit", function (e) {
    e.preventDefault();

    var form = $(this);
    var submitBtn = form.find('button[type="submit"]');
    var originalText = submitBtn.text();

    // Show loading state
    submitBtn
      .prop("disabled", true)
      .html('<span class="loading"></span> Sending...');

    $.ajax({
      url: form.attr("action"),
      method: form.attr("method"),
      data: form.serialize(),
      dataType: "json",
      success: function (response) {
        if (response.success) {
          showAlert(
            "success",
            response.message || "Form submitted successfully!"
          );
          form[0].reset();
        } else {
          showAlert(
            "danger",
            response.message || "An error occurred. Please try again."
          );
        }
      },
      error: function () {
        showAlert("danger", "An error occurred. Please try again.");
      },
      complete: function () {
        // Reset button state
        submitBtn.prop("disabled", false).text(originalText);
      },
    });
  });

  // Show alert function
  function showAlert(type, message) {
    var alertHtml =
      '<div class="alert alert-' +
      type +
      ' alert-dismissible fade show" role="alert">' +
      message +
      '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
      "</div>";

    $("#alertContainer").html(alertHtml);

    // Auto-hide after 5 seconds
    setTimeout(function () {
      $(".alert").fadeOut("slow");
    }, 5000);
  }

  // Image preview for file uploads
  $("#eventImage").on("change", function () {
    var file = this.files[0];
    if (file) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $("#imagePreview").attr("src", e.target.result).show();
      };
      reader.readAsDataURL(file);
    }
  });

  // Counter animation
  $(".counter").each(function () {
    var $this = $(this);
    var countTo = $this.attr("data-count");

    $({ countNum: $this.text() }).animate(
      {
        countNum: countTo,
      },
      {
        duration: 2000,
        easing: "swing",
        step: function () {
          $this.text(Math.floor(this.countNum));
        },
        complete: function () {
          $this.text(this.countNum);
        },
      }
    );
  });

  // Back to top button
  var backToTop = $(
    '<button class="btn btn-primary back-to-top" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000; display: none;"><i class="fas fa-arrow-up"></i></button>'
  );
  $("body").append(backToTop);

  $(window).scroll(function () {
    if ($(this).scrollTop() > 300) {
      backToTop.fadeIn();
    } else {
      backToTop.fadeOut();
    }
  });

  backToTop.on("click", function () {
    $("html, body").animate({ scrollTop: 0 }, 800);
  });

  // Mobile menu toggle
  $(".navbar-toggler").on("click", function () {
    $(this).toggleClass("active");
  });

  // Lazy loading for images
  if ("IntersectionObserver" in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const img = entry.target;
          img.src = img.dataset.src;
          img.classList.remove("lazy");
          imageObserver.unobserve(img);
        }
      });
    });

    document.querySelectorAll("img[data-src]").forEach((img) => {
      imageObserver.observe(img);
    });
  }

  // Theme toggle (if implemented)
  $("#themeToggle").on("click", function () {
    $("body").toggleClass("dark-theme");
    var isDark = $("body").hasClass("dark-theme");
    localStorage.setItem("darkTheme", isDark);
  });

  // Load saved theme preference
  if (localStorage.getItem("darkTheme") === "true") {
    $("body").addClass("dark-theme");
  }

  // Initialize any additional plugins
  if (typeof AOS !== "undefined") {
    AOS.init({
      duration: 800,
      easing: "ease-in-out",
      once: true,
    });
  }
});
