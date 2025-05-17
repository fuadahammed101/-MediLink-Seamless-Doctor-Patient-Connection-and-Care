<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MediLink Blog</title>
  <link rel="stylesheet" href="css/style.css" />
  <style>
    body {
      background: linear-gradient(120deg, #e8f5e9 0%, #b2dfdb 100%);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      min-height: 100vh;
    }
    header {
      background: #388e3c;
      color: #fff;
      padding: 2rem 0 1.2rem 0;
      text-align: center;
      box-shadow: 0 2px 12px rgba(44,62,80,0.08);
      position: sticky;
      top: 0;
      z-index: 10;
    }
    header h1 {
      margin: 0;
      font-size: 2.4rem;
      letter-spacing: 1px;
      font-weight: 800;
    }
    .blog-container {
      max-width: 900px;
      margin: 2.5rem auto 2rem auto;
      padding: 0 1.2rem;
      display: flex;
      flex-direction: column;
      gap: 2rem;
    }
    .blog-post {
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 4px 24px rgba(67,160,71,0.10);
      padding: 2rem 2.2rem 1.5rem 2.2rem;
      transition: box-shadow 0.2s, transform 0.2s;
      position: relative;
      overflow: hidden;
    }
    .blog-post:hover {
      box-shadow: 0 8px 32px rgba(67,160,71,0.16);
      transform: translateY(-3px) scale(1.015);
    }
    .blog-post h3 {
      margin: 0 0 0.5rem 0;
      font-size: 1.4rem;
      font-weight: 700;
      color: #2e7d32;
      letter-spacing: 0.5px;
    }
    .blog-post h3 a {
      color: #2e7d32;
      text-decoration: none;
      transition: color 0.2s;
    }
    .blog-post h3 a:hover {
      color: #43a047;
      text-decoration: underline;
    }
    .date {
      color: #757575;
      font-size: 0.98rem;
      margin-bottom: 0.7rem;
      font-style: italic;
    }
    .blog-post p {
      color: #444;
      font-size: 1.08rem;
      margin-bottom: 0.8rem;
      line-height: 1.6;
    }
    .read-more-btn {
      display: inline-block;
      background: linear-gradient(90deg, #43a047 60%, #66bb6a 100%);
      color: #fff;
      border: none;
      border-radius: 7px;
      padding: 0.5rem 1.3rem;
      font-size: 1rem;
      font-weight: 600;
      letter-spacing: 0.5px;
      cursor: pointer;
      transition: background 0.2s, box-shadow 0.2s;
      box-shadow: 0 2px 8px rgba(67,160,71,0.08);
      margin-top: 0.5rem;
      text-decoration: none;
    }
    .read-more-btn:hover {
      background: linear-gradient(90deg, #388e3c 60%, #43a047 100%);
      box-shadow: 0 4px 16px rgba(67,160,71,0.13);
    }
    .blog-actions {
      position: absolute;
      top: 1.2rem;
      right: 1.5rem;
      display: flex;
      gap: 0.7rem;
      z-index: 2;
    }
    .blog-action-btn {
      background: #e8f5e9;
      border: none;
      border-radius: 50%;
      width: 36px;
      height: 36px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-size: 1.2rem;
      color: #43a047;
      transition: background 0.2s, color 0.2s;
    }
    .blog-action-btn.liked {
      color: #e53935;
      background: #ffebee;
    }
    .blog-action-btn.bookmarked {
      color: #fbc02d;
      background: #fffde7;
    }
    .blog-action-btn:hover {
      background: #c8e6c9;
      color: #2e7d32;
    }
    .blog-post .likes-count {
      font-size: 0.98rem;
      color: #757575;
      margin-left: 0.2rem;
      vertical-align: middle;
    }
    .search-bar {
      max-width: 400px;
      margin: 0 auto 2rem auto;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(44,62,80,0.08);
      padding: 0.5rem 1rem;
    }
    .search-bar input {
      border: none;
      outline: none;
      font-size: 1.08rem;
      flex: 1;
      background: transparent;
      color: #333;
      padding: 0.5rem 0;
    }
    .search-bar button {
      background: #43a047;
      color: #fff;
      border: none;
      border-radius: 6px;
      padding: 0.5rem 1.1rem;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.2s;
    }
    .search-bar button:hover {
      background: #2e7d32;
    }
    @media (max-width: 600px) {
      .blog-container {
        padding: 0 0.2rem;
        gap: 1.2rem;
      }
      .blog-post {
        padding: 1.2rem 0.7rem 1rem 0.7rem;
      }
      .search-bar {
        padding: 0.3rem 0.5rem;
      }
    }
    /* Navbar styles for consistency */
    .navbar {
      background: #388e3c;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0.7rem 2rem;
      color: #fff;
    }
    .navbar .logo {
      font-size: 1.3rem;
      font-weight: 700;
      letter-spacing: 1px;
    }
    .nav-links {
      list-style: none;
      display: flex;
      gap: 1.2rem;
      margin: 0;
      padding: 0;
    }
    .nav-links li a {
      color: #fff;
      text-decoration: none;
      font-weight: 500;
      font-size: 1rem;
      transition: color 0.2s;
      padding: 0.3rem 0.7rem;
      border-radius: 5px;
    }
    .nav-links li a.active,
    .nav-links li a:hover {
      background: #2e7d32;
      color: #fffde7;
    }
    @media (max-width: 700px) {
      .navbar {
        flex-direction: column;
        align-items: flex-start;
        padding: 0.7rem 1rem;
      }
      .nav-links {
        flex-wrap: wrap;
        gap: 0.5rem;
      }
    }
  </style>
</head>
<body>

  <!-- Search Bar -->
  <div class="search-bar">
    <input type="text" id="blogSearchInput" placeholder="Search blog posts..." />
    <button id="blogSearchBtn">&#128269;</button>
  </div>

  <div class="blog-container" id="blogContainer">
    <!-- Blog posts will be rendered here by JS -->
  </div>

  <script>
    // Blog post data (add more as needed)
    const blogPosts = [
      {
        id: 1,
        title: "Have you heard of rheumatic heart disease (RHD)?",
        url: "blog1.html",
        date: "April 10, 2025",
        summary: "Learn about the most commonly prescribed medications and their uses to better understand your treatment options."
      },
      {
        id: 2,
        title: "Tips for Managing Chronic Conditions",
        url: "blog2.html",
        date: "April 8, 2025",
        summary: "Effective strategies and lifestyle changes to help manage chronic illnesses and improve quality of life."
      },
      {
        id: 3,
        title: "The Importance of Medication Adherence",
        url: "blog3.html",
        date: "April 5, 2025",
        summary: "Why taking your medications as prescribed is crucial for successful treatment outcomes."
      },
      {
        id: 4,
        title: "How to Read Prescription Labels",
        url: "blog4.html",
        date: "April 3, 2025",
        summary: "A guide to understanding the information on your medication labels to ensure safe usage."
      },
      {
        id: 5,
        title: "Natural Remedies vs. Prescription Drugs",
        url: "blog5.html",
        date: "March 30, 2025",
        summary: "Comparing natural remedies and prescription medications: benefits, risks, and when to choose each."
      },
      {
        id: 6,
        title: "Preventing Medication Side Effects",
        url: "blog6.html",
        date: "March 28, 2025",
        summary: "Tips to minimize and manage side effects from your medications effectively."
      },
      {
        id: 7,
        title: "Understanding Antibiotic Resistance",
        url: "blog7.html",
        date: "March 25, 2025",
        summary: "What antibiotic resistance means and how to prevent it through responsible medication use."
      },
      {
        id: 8,
        title: "Medication Safety for Seniors",
        url: "blog8.html",
        date: "March 22, 2025",
        summary: "Special considerations for medication management in older adults to ensure safety and effectiveness."
      },
      {
        id: 9,
        title: "How to Store Your Medications Properly",
        url: "blog9.html",
        date: "March 20, 2025",
        summary: "Best practices for storing medications to maintain their potency and prevent accidents."
      },
      {
        id: 10,
        title: "When to Consult Your Pharmacist",
        url: "blog10.html",
        date: "March 18, 2025",
        summary: "Learn when and why you should seek advice from your pharmacist regarding your medications."
      }
    ];

    // Like/bookmark state (simulate with localStorage)
    function getLikes() {
      return JSON.parse(localStorage.getItem("blogLikes") || "{}");
    }
    function setLikes(likes) {
      localStorage.setItem("blogLikes", JSON.stringify(likes));
    }
    function getBookmarks() {
      return JSON.parse(localStorage.getItem("blogBookmarks") || "[]");
    }
    function setBookmarks(bookmarks) {
      localStorage.setItem("blogBookmarks", JSON.stringify(bookmarks));
    }

    // Render blog posts
    function renderBlogPosts(posts) {
      const likes = getLikes();
      const bookmarks = getBookmarks();
      document.getElementById("blogContainer").innerHTML = posts.map(post => `
        <article class="blog-post" data-id="${post.id}">
          <div class="blog-actions">
            <button class="blog-action-btn like-btn${likes[post.id] ? ' liked' : ''}" title="Like">
              &#10084;<span class="likes-count">${likes[post.id] || 0}</span>
            </button>
            <button class="blog-action-btn bookmark-btn${bookmarks.includes(post.id) ? ' bookmarked' : ''}" title="Bookmark">
              &#9733;
            </button>
          </div>
          <h3><a href="${post.url}">${post.title}</a></h3>
          <div class="date">${post.date}</div>
          <p>${post.summary}</p>
          <a href="${post.url}" class="read-more-btn">Read More</a>
        </article>
      `).join('');
    }

    // Initial render
    renderBlogPosts(blogPosts);

    // Like and bookmark handlers
    document.getElementById("blogContainer").addEventListener("click", function(e) {
      const postEl = e.target.closest(".blog-post");
      if (!postEl) return;
      const postId = parseInt(postEl.getAttribute("data-id"));
      // Like
      if (e.target.closest(".like-btn")) {
        const likes = getLikes();
        likes[postId] = (likes[postId] || 0) + 1;
        setLikes(likes);
        renderBlogPosts(filterPosts(document.getElementById("blogSearchInput").value));
      }
      // Bookmark
      if (e.target.closest(".bookmark-btn")) {
        let bookmarks = getBookmarks();
        if (bookmarks.includes(postId)) {
          bookmarks = bookmarks.filter(id => id !== postId);
        } else {
          bookmarks.push(postId);
        }
        setBookmarks(bookmarks);
        renderBlogPosts(filterPosts(document.getElementById("blogSearchInput").value));
      }
    });

    // Search functionality
    function filterPosts(query) {
      query = (query || "").toLowerCase();
      return blogPosts.filter(post =>
        post.title.toLowerCase().includes(query) ||
        post.summary.toLowerCase().includes(query)
      );
    }
    document.getElementById("blogSearchInput").addEventListener("input", function() {
      renderBlogPosts(filterPosts(this.value));
    });
    document.getElementById("blogSearchBtn").addEventListener("click", function() {
      const val = document.getElementById("blogSearchInput").value;
      renderBlogPosts(filterPosts(val));
    });
  </script>
</body>
</html>