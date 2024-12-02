const API_BASE_URL = "http://api.cc.localhost"; 

document.addEventListener("DOMContentLoaded", () => {
  loadCategories();
  loadCourses();
});

async function loadCategories() {
  try {
    // Fetch categories with hierarchy from the API
    const response = await fetch(`${API_BASE_URL}/categories-hierarchy`);
    const categories = await response.json();

    const categoriesContainer = document.getElementById("categories");

    // Recursive function to generate HTML for categories and subcategories
    function generateCategoryHtml(category) {
      // Generate subcategories recursively
      const subcategoriesHtml = (category.subcategories || [])
        .map((sub) => `
          <li class="subcategory-item" data-id="${sub.id}">
            ${sub.count_of_courses > 0 ? `${sub.name} (${sub.count_of_courses})` : `${sub.name}`} 
            ${sub.subcategories ? generateSubcategoriesHtml(sub.subcategories) : ''}
          </li>`)
        .join("");

      return `
        <div class="main-category" data-id="${category.id}">
          <h3>${category.count_of_courses > 0 ? `${category.name} (${category.count_of_courses})` : `${category.name}`}</h3>
          <ul>${subcategoriesHtml}</ul>
        </div>`;
    }

    // Function to handle rendering nested subcategories recursively
    function generateSubcategoriesHtml(subcategories) {
      return `
        <ul>
          ${subcategories.map(sub => `
            <li class="subcategory-item" data-id="${sub.id}">
              ${sub.count_of_courses > 0 ? `${sub.name} (${sub.count_of_courses})` : `${sub.name}`}
              ${sub.subcategories ? generateSubcategoriesHtml(sub.subcategories) : ''}
            </li>
          `).join('')}
        </ul>
      `;
    }

    // Generate HTML for all top-level categories
    categoriesContainer.innerHTML = Object.entries(categories)
      .map(([categoryId, category]) => generateCategoryHtml(category))
      .join("");

    // Add event listeners for filtering courses by category or subcategory
    document.querySelectorAll(".main-category").forEach((mainItem) =>
      mainItem.addEventListener("click", (e) => {
        e.stopPropagation(); // Prevent bubbling to parent elements
        const categoryId = mainItem.dataset.id;
        loadCourses(categoryId); // Load courses for the main category
      })
    );

    document.querySelectorAll(".subcategory-item").forEach((subItem) =>
      subItem.addEventListener("click", (e) => {
        e.stopPropagation(); // Prevent bubbling to parent elements
        const subcategoryId = subItem.dataset.id;
        loadCourses(subcategoryId); // Load courses for the subcategory
      })
    );
  } catch (error) {
    console.error("Error loading categories:", error);
  }
}

async function loadCourses(categoryId = "") {
  try {
    const url = categoryId
      ? `${API_BASE_URL}/courses/all?category_id=${categoryId}`
      : `${API_BASE_URL}/courses`;

    const response = await fetch(url);
    const courses = await response.json();

    const coursesContainer = document.getElementById("courses");
    coursesContainer.innerHTML = courses
      .map(
        (course) =>
          `<div class="course-card">
            <div class="category_name">${course.category_name}</div>
            <img src="${course.image_preview}" alt="${course.name} image" class="course-image" />
            <h3>${course.title.substring(0, 30)}. . .</h3>
            <p>${course.description.substring(0, 100)}. . .</p>
          </div>`
      )
      .join("");
  } catch (error) {
    console.error("Error loading courses:", error);
  }
}
