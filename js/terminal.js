jQuery(document).ready(function ($) {
  // **COMMENT OUT OR REMOVE THIS ENTIRE SECTION**
  // CATEGORY MENU LINK FILTERING
  // $(".studio-category-link").on("click", function (e) {
  //   e.preventDefault();
  //   $(".studio-category-link").removeClass("active");
  //   $(this).addClass("active");

  //   // Clear tag dropdown
  //   $("#tag-filter").val("");

  //   const catSlug = $(this).data("cat-id");
  //   $(".terminal-table tbody tr").each(function () {
  //     const rowCat = $(this).data("category");
  //     if (!catSlug || rowCat === catSlug) {
  //       $(this).show();
  //     } else {
  //       $(this).hide();
  //     }
  //   });
  // });

  // TAG DROPDOWN FILTERING
  $("#tag-filter").on("change", function () {
    $(".studio-category-link").removeClass("active");
    const selectedTag = $(this).val();
    $(".terminal-table tbody tr").each(function () {
      const rowTags = ($(this).data("tags") || "").split(" ");
      if (!selectedTag || rowTags.includes(selectedTag)) {
        $(this).show();
      } else {
        $(this).hide();
      }
    });
  });

  // SEARCH FILTER (optional, keep if you want)
  $(".terminal-search").on("input", function () {
    const searchTerm = $(this).val().toLowerCase();
    $(".terminal-table tbody tr").each(function () {
      const rowText = $(this).text().toLowerCase();
      if (rowText.includes(searchTerm)) {
        $(this).show();
      } else {
        $(this).hide();
      }
    });
    // Reset filters
    $(".studio-category-link").removeClass("active");
    $("#tag-filter").val("");
  });

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

  // Tag filter functionality
  $("#tag-filter").on("change", function () {
    const selectedTag = $(this).val();

    if (selectedTag === "") {
      // Show all rows if "All Tags" is selected
      $(".terminal-table tbody tr").show();
    } else {
      // Filter rows based on tag
      $(".terminal-table tbody tr").each(function () {
        const rowTags = $(this).data("tags");

        if (rowTags && rowTags.includes(selectedTag)) {
          $(this).show();
        } else {
          $(this).hide();
        }
      });
    }
  });

  // Also make sure category filter is working
  $("#category-filter").on("change", function () {
    const selectedCategory = $(this).val();

    if (selectedCategory === "") {
      // Show all rows if "All Categories" is selected
      $(".terminal-table tbody tr").show();
    } else {
      // Filter rows based on category
      $(".terminal-table tbody tr").each(function () {
        const rowCategory = $(this).data("category");

        if (rowCategory === selectedCategory) {
          $(this).show();
        } else {
          $(this).hide();
        }
      });
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

  $(".terminal-table th").on("click", function () {
    var table = $(this).closest("table.terminal-table");
    var tbody = table.find("tbody");
    var rows = tbody.find("tr").toArray();
    var colIndex = $(this).index();
    var isAsc = $(this).hasClass("sorted-asc");

    // Remove sort classes from all headers and reset indicators
    table.find("th").removeClass("sorted-asc sorted-desc");
    table.find(".sort-indicator").text("▲▼");

    // Sort rows
    rows.sort(function (a, b) {
      var aText = $(a).find("td").eq(colIndex).text().toLowerCase();
      var bText = $(b).find("td").eq(colIndex).text().toLowerCase();
      if (aText < bText) return isAsc ? 1 : -1;
      if (aText > bText) return isAsc ? -1 : 1;
      return 0;
    });

    // Toggle sort direction class and indicator
    if (isAsc) {
      $(this).addClass("sorted-desc");
      $(this).find(".sort-indicator").text("▼");
    } else {
      $(this).addClass("sorted-asc");
      $(this).find(".sort-indicator").text("▲");
    }

    // Append sorted rows
    $.each(rows, function (i, row) {
      tbody.append(row);
    });
  });

  $(".ascii-title").on("click", function () {
    // Remove active state from all category links
    $(".studio-category-link").removeClass("active");
    // Reset tag dropdown
    $("#tag-filter").val("");
    // Clear search box
    $(".terminal-search").val("");
    // Show all table rows
    $(".terminal-table tbody tr").show();
  });
});
