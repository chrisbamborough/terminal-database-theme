console.log("Arena.js script loaded!");

jQuery(document).ready(function ($) {
  console.log("jQuery ready fired!");

  // Test if containers exist
  console.log("Testing containers:");
  console.log("Arena content container:", $("#arena-content").length);
  console.log("Imgexhaust container:", $("#imgexhaust-feed").length);
  console.log("Arena ticker container:", $("#arena-ticker-text").length);

  // Main initialization function
  async function initializeArenaFeeds() {
    try {
      // First, load the main arena content if it exists (for arena page)
      const mainArenaContainer = $("#arena-content");
      if (mainArenaContainer.length) {
        const channelSlug = mainArenaContainer.data("channel");
        if (channelSlug) {
          console.log("Loading main arena feed:", channelSlug);
          await loadMainArenaFeed(channelSlug);
          console.log("Main arena feed loaded successfully");
        }
      }

      // Load the ticker feed (can be on any page)
      const tickerContainer = $("#arena-ticker-text");
      if (tickerContainer.length) {
        // Always use the ticker's own data-channel attribute
        const tickerChannelSlug =
          tickerContainer.data("channel") || "photo-album-v7neum9xvi4";
        console.log("Loading ticker feed:", tickerChannelSlug);
        await loadTickerFeed(tickerChannelSlug);
        console.log("Ticker feed loaded successfully");
      }

      // Load the imgexhaust feed independently (for writing page)
      const imgexhaustContainer = $("#imgexhaust-feed");
      if (imgexhaustContainer.length) {
        const imgChannelSlug = imgexhaustContainer.data("channel");
        if (imgChannelSlug) {
          console.log("Loading imgexhaust feed:", imgChannelSlug);
          console.log("Imgexhaust container found:", imgexhaustContainer);
          await loadImgexhaustFeed(imgChannelSlug);
          console.log("Imgexhaust feed loaded successfully");
        } else {
          console.log("No imgexhaust channel slug found");
        }
      } else {
        console.log("No imgexhaust container found");
      }
    } catch (error) {
      console.error("Error initializing arena feeds:", error);
    }
  }

  // Load ticker feed
  function loadTickerFeed(channelSlug) {
    return new Promise((resolve, reject) => {
      fetch(`https://api.are.na/v2/channels/${channelSlug}?per=10`)
        .then((response) => {
          if (!response.ok) {
            throw new Error("Network response was not ok");
          }
          return response.json();
        })
        .then((data) => {
          displayTickerContent(data);
          resolve(data);
        })
        .catch((error) => {
          console.error("Error fetching ticker Arena data:", error);
          const tickerText = $("#arena-ticker-text");
          if (tickerText.length) {
            tickerText.text("Click to explore Are.na feed");
          }
          reject(error);
        });
    });
  }

  // Display ticker content
  function displayTickerContent(data) {
    const tickerText = $("#arena-ticker-text");

    if (!data || !data.contents || data.contents.length === 0) {
      tickerText.text("Click to explore Are.na feed");
      return;
    }

    // Get the most recent blocks (they come in chronological order, so reverse to get newest first)
    const recentBlocks = data.contents.slice().reverse();

    // Find the most recent block with content
    const recentBlock = recentBlocks.find(
      (block) =>
        block.description ||
        block.title ||
        (block.content && block.class === "Text")
    );

    if (recentBlock) {
      let tickerContent = "";

      if (recentBlock.description) {
        tickerContent = recentBlock.description;
      } else if (recentBlock.title) {
        tickerContent = recentBlock.title;
      } else if (recentBlock.content && recentBlock.class === "Text") {
        tickerContent = recentBlock.content.substring(0, 100) + "...";
      }

      tickerText.text(`Latest from Are.na: ${tickerContent}`);
    } else {
      tickerText.text("Click to explore Are.na feed");
    }
  }

  // Load main arena feed (for arena page)
  function loadMainArenaFeed(channelSlug) {
    return new Promise((resolve, reject) => {
      fetch(`https://api.are.na/v2/channels/${channelSlug}?per=100`)
        .then((response) => {
          if (!response.ok) {
            throw new Error("Network response was not ok");
          }
          return response.json();
        })
        .then((data) => {
          displayMainArenaContent(data); // Changed from displayArenaContent(data)
          resolve(data);
        })
        .catch((error) => {
          console.error("Error fetching main Arena data:", error);
          $("#arena-content").html(
            '<div class="error-message">Error loading Arena content. Please try again later.</div>'
          );
          reject(error);
        });
    });
  }

  // Load imgexhaust feed (for writing page)
  function loadImgexhaustFeed(channelSlug) {
    const container = $("#imgexhaust-feed");

    console.log("Starting imgexhaust load with channel:", channelSlug);
    console.log("Container exists:", container.length > 0);

    return new Promise((resolve, reject) => {
      // Show loading state
      container.html(
        '<div class="imgexhaust-item"><div class="post-title">Loading...</div></div>'
      );

      const apiUrl = `https://api.are.na/v2/channels/${channelSlug}?per=10`;
      console.log("Making request to:", apiUrl);

      fetch(apiUrl)
        .then((response) => {
          console.log("Imgexhaust response status:", response.status);
          console.log("Imgexhaust response ok:", response.ok);

          if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
          }
          return response.json();
        })
        .then((data) => {
          console.log("Imgexhaust raw data:", data);
          console.log(
            "Contents length:",
            data.contents ? data.contents.length : "no contents"
          );
          displayImgexhaustContent(data);
          resolve(data);
        })
        .catch((error) => {
          console.error("Detailed imgexhaust error:", error);
          container.html(`
            <div class="imgexhaust-item">
              <div class="post-title">Error: ${error.message}</div>
              <div class="post-meta">
                <span class="post-type">Error</span>
                <span class="post-date">—</span>
              </div>
            </div>
          `);
          reject(error);
        });
    });
  }

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

  // Display imgexhaust content
  function displayImgexhaustContent(data) {
    console.log("displayImgexhaustContent called with:", data);

    const container = $("#imgexhaust-feed");

    if (!data || !data.contents || data.contents.length === 0) {
      console.log("No data or contents found");
      container.html(`
        <div class="imgexhaust-item">
          <div class="post-title">No content found</div>
          <div class="post-meta">
            <span class="post-type">—</span>
            <span class="post-date">—</span>
          </div>
        </div>
      `);
      return;
    }

    container.empty();

    // Get first 4 blocks
    const blocks = data.contents.slice(0, 4);
    console.log("Processing blocks:", blocks.length);

    blocks.forEach((block, index) => {
      console.log(`Processing block ${index}:`, block);

      const createdDate = new Date(block.created_at);
      const formattedDate = createdDate
        .toLocaleDateString("en-US", {
          year: "numeric",
          month: "2-digit",
        })
        .replace(/\//g, "-");

      // Create arena block URL
      const arenaBlockUrl = `https://www.are.na/block/${block.id}`;

      // Get source URL if available, otherwise use arena block URL
      const sourceUrl = block.source?.url || arenaBlockUrl;

      const blockHtml = `
        <div class="imgexhaust-item">
          <div class="post-title">
            <a href="${arenaBlockUrl}" target="_blank" rel="noopener noreferrer">
              ${block.title || "Untitled"}
            </a>
          </div>
          <div class="post-meta">
            <a href="${sourceUrl}" target="_blank" rel="noopener noreferrer" class="post-type">
              ${block.class || "Block"}
            </a>
            <span class="post-date">${formattedDate}</span>
          </div>
        </div>
      `;

      container.append(blockHtml);
    });

    console.log("Imgexhaust content display complete");
  }

  // Display main arena content
  function displayMainArenaContent(data) {
    console.log("displayMainArenaContent called with:", data);

    const container = $("#arena-content");

    if (!data || !data.contents || data.contents.length === 0) {
      console.log("No data or contents found");
      container.html('<div class="loading-message">No content found</div>');
      return;
    }

    container.empty();

    // Reverse the order to show most recent first
    const blocks = data.contents.slice().reverse();
    console.log("Processing blocks (newest first):", blocks.length);

    blocks.forEach((block, index) => {
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

  // Start the initialization process
  initializeArenaFeeds();
});
