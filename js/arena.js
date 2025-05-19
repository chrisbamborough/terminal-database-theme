jQuery(document).ready(function ($) {
  // Get the Arena channel slug from the data attribute
  const channelSlug = $("#arena-content").data("channel");
  if (!channelSlug) {
    console.error("No Arena channel specified");
    return;
  }

  // Fetch data from Arena API
  fetch(`https://api.are.na/v2/channels/${channelSlug}?per=100`)
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      displayArenaContent(data);
    })
    .catch((error) => {
      console.error("Error fetching Arena data:", error);
      $("#arena-content").html(
        '<div class="error-message">Error loading Arena content. Please try again later.</div>'
      );
    });

  // Display the Arena content in the grid
  function displayArenaContent(data) {
    const container = $("#arena-content");
    container.empty();

    if (!data || !data.contents || data.contents.length === 0) {
      container.html(
        '<div class="empty-message">No content found in this Arena channel.</div>'
      );
      return;
    }

    // Set the page title
    document.title =
      data.title + " | " + document.title.split(" | ")[1] || "Arena";

    // Loop through and display each block
    data.contents.forEach((block) => {
      // Format date nicely
      const createdDate = new Date(block.created_at);
      const formattedDate = createdDate.toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
      });

      let blockHtml = "";
      let blockText = block.title || "";

      // Determine appropriate content for block
      if (block.description) {
        blockText = block.description;
      } else if (block.content && block.class === "Text") {
        blockText =
          block.content.substring(0, 250) +
          (block.content.length > 250 ? "..." : "");
      } else if (block.source && block.source.url) {
        blockText = block.source.url;
      }

      if (block.class === "Image") {
        blockHtml = `
          <div class="arena-block image" data-id="${
            block.id
          }" data-type="${block.class.toLowerCase()}">
            <div class="arena-image-container">
              <a href="${
                block.source?.url || block.image.original.url
              }" target="_blank" rel="noopener noreferrer">
                <img src="${block.image.display.url}" alt="${
          block.title || "Arena image"
        }" />
              </a>
            </div>
            <div class="arena-content-info">
              <div class="arena-title">${block.title || "—"}</div>
              <div class="arena-description">${blockText}</div>
              <div class="arena-date">${formattedDate}</div>
            </div>
          </div>
        `;
      } else if (block.class === "Text") {
        blockHtml = `
          <div class="arena-block text" data-id="${
            block.id
          }" data-type="${block.class.toLowerCase()}">
            <div class="arena-image-container text-placeholder">
              <div class="text-indicator">TEXT</div>
            </div>
            <div class="arena-content-info">
              <div class="arena-title">${block.title || "Text"}</div>
              <div class="arena-description">${blockText}</div>
              <div class="arena-date">${formattedDate}</div>
            </div>
          </div>
        `;
      } else if (block.class === "Link") {
        if (block.image) {
          blockHtml = `
            <div class="arena-block link" data-id="${
              block.id
            }" data-type="${block.class.toLowerCase()}">
              <div class="arena-image-container">
                <a href="${
                  block.source.url
                }" target="_blank" rel="noopener noreferrer">
                  <img src="${block.image.display.url}" alt="${
            block.title || "Arena link"
          }" />
                </a>
              </div>
              <div class="arena-content-info">
                <div class="arena-title">${block.title || "Link"}</div>
                <div class="arena-description">${blockText}</div>
                <div class="arena-date">${formattedDate}</div>
              </div>
            </div>
          `;
        } else {
          blockHtml = `
            <div class="arena-block link" data-id="${
              block.id
            }" data-type="${block.class.toLowerCase()}">
              <div class="arena-image-container link-placeholder">
                <div class="link-indicator">LINK</div>
              </div>
              <div class="arena-content-info">
                <div class="arena-title">${block.title || "Link"}</div>
                <div class="arena-description">${blockText}</div>
                <div class="arena-date">${formattedDate}</div>
              </div>
            </div>
          `;
        }
      } else if (block.class === "Attachment") {
        blockHtml = `
          <div class="arena-block attachment" data-id="${
            block.id
          }" data-type="${block.class.toLowerCase()}">
            <div class="arena-image-container attachment-placeholder">
              <div class="attachment-indicator">FILE</div>
            </div>
            <div class="arena-content-info">
              <div class="arena-title">${block.title || "Attachment"}</div>
              <div class="arena-description">${blockText}</div>
              <div class="arena-date">${formattedDate}</div>
            </div>
          </div>
        `;
      } else {
        blockHtml = `
          <div class="arena-block unknown" data-id="${
            block.id
          }" data-type="${block.class.toLowerCase()}">
            <div class="arena-image-container unknown-placeholder">
              <div class="unknown-indicator">${block.class}</div>
            </div>
            <div class="arena-content-info">
              <div class="arena-title">${block.title || block.class}</div>
              <div class="arena-description">${blockText}</div>
              <div class="arena-date">${formattedDate}</div>
            </div>
          </div>
        `;
      }

      container.append(blockHtml);
    });

    // Add search functionality
    $(".arena-search").on("input", function () {
      const searchText = $(this).val().toLowerCase();
      $(".arena-block").each(function () {
        const blockText = $(this).text().toLowerCase();
        if (blockText.includes(searchText)) {
          $(this).show();
        } else {
          $(this).hide();
        }
      });
    });
  }
});
