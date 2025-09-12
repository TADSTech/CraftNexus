craftnexus/
│
├── frontend/                # Partner's area (HTML, CSS, JS)
│   ├── artisans.html        # Artisan directory
│   ├── artisan.html         # Single artisan profile
│   ├── project.html         # Project request form
│   ├── login.html           # Simple login page
│   │
│   ├── css/
│   │   └── style.css        # Main stylesheet
│   │
│   └── js/
│       ├── main.js          # Common scripts (nav, etc.)
│       ├── artisans.js      # Fetch + render artisans list
│       ├── artisan.js       # Fetch + render artisan profile
│       ├── project.js       # Handle project request form
│       └── login.js         # Handle login form
│
├── backend/                 # Your area (PHP + MySQL)
│   ├── db.php               # Database connection
│   ├── auth.php             # User login/logout
│   ├── artisans.php         # GET all artisans
│   ├── artisan.php          # GET single artisan details
│   ├── project_request.php  # POST new project request
│   ├── portfolio_upload.php # (Optional) Upload artisan portfolio item
│   ├── analytics.php        # GET project/skill statistics
│   │
│   └── sql/
│       └── schema.sql       # Database schema for quick setup
│
├── assets/                  # Shared images, icons, logos
│   └── logo.png
│
├── README.md                # Overview + setup instructions
└── .gitignore               # Ignore vendor/temp files
|_ index.html           # Landing page
