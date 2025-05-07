jQuery(document).ready(function ($) {
  // Sort functionality
  $(".terminal-sort").click(function () {
    const column = $(this).data("column");
    const direction = $(this).hasClass("asc") ? "desc" : "asc";

    // Reset all sort indicators
    $(".terminal-sort").removeClass("asc desc");
    $(this).addClass(direction);

    sortTable(column, direction);
  });

  // Simple search functionality
  $(".terminal-search").on("input", function () {
    const searchTerm = $(this).val().toLowerCase();
    filterTable(searchTerm);

    // Reset category filter when searching
    $("#category-filter").val("");
  });

  // Category filter functionality
  $("#category-filter").change(function () {
    const category = $(this).val();

    // Clear search when using category filter
    $(".terminal-search").val("");

    if (category === "") {
      $(".terminal-table tbody tr").show();
    } else {
      $(".terminal-table tbody tr").hide();
      $('.terminal-table tbody tr[data-category="' + category + '"]').show();
    }
  });

  // Simplified image hover functionality - always position next to mouse
  $(".terminal-table tr.has-image").on({
    mouseenter: function (e) {
      const imageUrl = $(this).data("image");

      if (imageUrl) {
        // Set the image source
        $("#preview-image").attr("src", imageUrl);

        // Get mouse position relative to the document
        const mouseX = e.pageX;
        const mouseY = e.pageY;

        // Show and position the modal next to cursor
        $(".image-modal")
          .css({
            top: mouseY + "px",
            left: mouseX + 20 + "px", // 20px offset to the right of cursor
          })
          .addClass("visible");
      }
    },
    mouseleave: function () {
      $(".image-modal").removeClass("visible");
    },
    mousemove: function (e) {
      // Always update position on mouse move
      if ($(".image-modal").hasClass("visible")) {
        // Get mouse position relative to the document
        const mouseX = e.pageX;
        const mouseY = e.pageY;

        // Update position to follow cursor
        $(".image-modal").css({
          top: mouseY + "px",
          left: mouseX + 20 + "px", // 20px offset to the right of cursor
        });
      }
    },
  });

  // Additional hover functionality
  $(".terminal-table tr.has-image").on({
    mouseenter: function (e) {
      var imageUrl = $(this).data("image");
      if (imageUrl) {
        $("<div class='hover-image'><img src='" + imageUrl + "'></div>")
          .css({
            position: "absolute",
            top: e.pageY + 20,
            left: e.pageX + 20,
            zIndex: 1000,
          })
          .appendTo("body");
      }
    },
    mouseleave: function () {
      $(".hover-image").remove();
    },
    mousemove: function (e) {
      $(".hover-image").css({
        top: e.pageY + 20,
        left: e.pageX + 20,
      });
    },
  });

  function sortTable(column, direction) {
    const rows = $(".terminal-table tbody tr").toArray();

    rows.sort(function (a, b) {
      const aText = $(a).find("td").eq(column).text().toLowerCase();
      const bText = $(b).find("td").eq(column).text().toLowerCase();

      if (direction === "asc") {
        return aText.localeCompare(bText);
      } else {
        return bText.localeCompare(aText);
      }
    });

    $.each(rows, function (index, row) {
      $(".terminal-table tbody").append(row);
    });
  }

  function filterTable(searchTerm) {
    $(".terminal-table tbody tr").each(function () {
      const rowText = $(this).text().toLowerCase();
      if (rowText.includes(searchTerm)) {
        $(this).show();
      } else {
        $(this).hide();
      }
    });
  }

  // Name animation for landing page
  if (
    document.getElementById("first-name") &&
    document.getElementById("last-name")
  ) {
    animateName("first-name", "Christopher");
    animateName("last-name", "Bamborough");
  }

  function animateName(elementId, name) {
    const nameElement = document.getElementById(elementId);

    // Clear the content first
    nameElement.textContent = "";

    // Create a span for each character
    name.split("").forEach((char) => {
      const span = document.createElement("span");
      span.className = "pixel-char";
      span.textContent = "_"; // Placeholder character
      nameElement.appendChild(span);
    });

    // Animation logic
    const spans = nameElement.querySelectorAll(".pixel-char");
    const chars =
      "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@#$%^&*()_+-=[]{}|;:,./<>?";

    spans.forEach((span, index) => {
      const originalChar = name[index];

      let iterations = 0;
      const maxIterations = 10 + Math.floor(Math.random() * 10);

      // Stagger the start of each character's animation
      setTimeout(() => {
        const interval = setInterval(() => {
          span.textContent = chars[Math.floor(Math.random() * chars.length)];
          iterations++;

          if (iterations >= maxIterations) {
            clearInterval(interval);
            span.textContent = originalChar;
            span.classList.add("revealed");
          }
        }, 50);
      }, index * 100);
    });
  }
});
