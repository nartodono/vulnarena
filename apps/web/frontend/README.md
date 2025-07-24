This directory contains the source code for the server-side application that users interact with directly.

## Tech Stack

- **HTML**: Provides the overall structure and layout of the web pages.
- **CSS (Tailwind)**: Styles and beautifies the interface for a modern, responsive look.
- **JavaScript**: Enhances the user experience with interactive UI elements, handles API requests via `fetch`, and dynamically displays data on the website.
- **PHP (Native)**: Handles server-side routing and page authorization checks, primarily using `file_get_contents` for communication with the backend API within the local Minikube environment.

---

The frontend is responsible for serving the main website, rendering content to users, and interacting with the backend API for data and business logic.  
**Note:** The backend API is a completely separate service, accessible directly via its own subdomain and not limited to requests from the frontend only.
