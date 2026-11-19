# Narramus

## Overview

The Librarian is a semi-automatic tool, based on Story Map Building and Visualization Tool (SMBVT), for constructing and visualizing narratives as semantic networks of events related through semantic relations. It presents narratives in the form of interactive story maps, timelines, and 3D visualizations.

SMBVT is an extension of the previously developed [Narrative Building and Visualisation Tool (NBVT)](http://dlnarratives.eu/tool/index.html) and obeys a dedicated [ontology for narratives](https://dlnarratives.eu/ontology.html).

## Key Features

### Core Functionality
- **Narrative Construction**: Build complex narratives using semantic relationships between events
- **Multiple Visualization Modes**:
  - Interactive story maps with geographical data
  - Timeline views
  - 3D model integration (Sketchfab and Aton viewers)
  - Slide-based presentations
- **Entity Management**: Organize entities by class (persons, places, organizations, works, etc.)
- **Wikidata Integration**: Search and import entities directly from Wikidata

### Advanced Features
- **Collaborative Editing**: User accounts and narrative management
- **Export Formats**: OWL (RDF) and JSON exports for interoperability
- **Graph Visualization**: Create and publish narrative graphs
- **Media Integration**: Upload images, 3D models, and other media assets
- **Geospatial Tools**: Draw polygons and mark coordinates on maps

## Project Team

### Collaborators
- Valentina Bartalesi
- Nicolò Pratelli
- Emanuele Lenzi
- Claudio De Martino

### Developers
- Emanuele Lenzi
- Nicolò Pratelli
- Claudio De Martino (previous SMBVT version)
- Daniele Metilli (previous NBVT version)

## Citation

If you use The Librarian in your research, please cite:

> Bartalesi, V., Lenzi, E., De Martino, C., Coro, G. *[Interactive story maps for historical musical instruments: a 3D and semantic web tool for cultural heritage preservation](https://doi.org/10.1007/s41060-025-01014-4)*. Int J Data Sci Anal 22, 21. (2025)

> Meghini C., Bartalesi V., Metilli D. *[Representing narratives in digital libraries: The narrative ontology](https://content.iospress.com/articles/semantic-web/sw200421)*. In: Semantic Web, vol. 12, no. 2, pp. 241-264, 2021.

> Metilli D., Bartalesi V., Meghini C. *[A Wikidata-based tool for building and visualising narratives](https://link.springer.com/article/10.1007/s00799-019-00266-3)*. In: International Journal on Digital Libraries, vol. Springer, 2019.

> Metilli D., Bartalesi V., Meghini C. *[Populating narratives using Wikidata events: an initial experiment](https://link.springer.com/chapter/10.1007/978-3-030-11226-4_13)*. In: Digital Libraries: Supporting Open Science. 15th Italian Research Conference on Digital Libraries, pp. 159 - 166. Manghi P., Candela L., Silvello G. (eds.). (Communications in Computer and Information Science, vol. 988). Pisa: Springer, 2019.

## System Architecture

### Frontend Components

#### Main Pages
- **`SMBVT_2.0.html`**: Home page with user authentication, narrative list, and creation options
- **`toolNew.html`**: Main narrative builder interface
- **`demo.html`**: Demo version for trying the tool without authentication

#### Key JavaScript Modules
- **`demo-narraMapsNew.ui.js`**: User interface and event handling
- **`demo-narraMapsNew.map.js`**: Map-based event management
- **`demo-narraMapsNew.wikidata.js`**: Wikidata integration
- **`demo-narraMapsNew.persistence.js`**: Database operations and narrative persistence
- **`demo-narraMapsNew.sketchfab.js`**: Sketchfab 3D model integration
- **`demo-narraMapsNew.aton.js`**: Aton 3D viewer integration
- **`demo-narraMapsNew.events.js`**: Event management and timeline functionality

### Backend Components

#### PHP API
- **`PHP/session.php`**: User authentication and session management
- **`PHP/insertDb.php`**: Database insertion operations
- **`PHP/deleteDb.php`**: Database deletion operations
- **`PHP/publishMap.php`**: Narrative graph generation
- **`PHP/triplify.php`**: OWL/RDF export generation
- **`PHP/api_helpers.php`**: Helper functions for API operations

#### Database
- **PostgreSQL**: Primary database for storing narratives and user data
- **Apache Jena Fuseki**: SPARQL endpoint for semantic queries

## Installation

### Prerequisites

- [PostgreSQL](https://www.postgresql.org/) (for narrative storage)
- [Apache Jena Fuseki](https://jena.apache.org/documentation/fuseki2/) (for SPARQL endpoint)
- Apache web server with PHP support
- PHP 7.4 or later

### Setup Instructions

1. **Database Configuration**
   - Install PostgreSQL and create a database for SMBVT
   - Configure connection settings in `PHP/PgConn.php`

2. **Triple Store Setup**
   - Install Apache Jena Fuseki
   - Create a dataset named "narratives"
   - Configure the public endpoint at "narratives/query"

3. **Web Server Configuration**
   - Upload all files to your web server's document root or XAMPP's `htdocs` folder
   - Ensure PHP is properly configured
   - Ensure the PHP web-server user can write to `json/`, `owl/`, `storymaps/`, and `TempJson/`; these output directories must exist or their parent directories must allow PHP to create them. Do not make the entire document root writable.

4. **User Management**
   - Users can request credentials via email (contact: emanuele.lenzi@isti.cnr.it)
   - Demo version available at `demo.html` for testing

## Usage

### Getting Started

1. **Access the Tool**: Navigate to `SMBVT_2.0.html` and log in with your credentials
2. **Create a Narrative**: 
   - Search for a subject on Wikidata
   - Or import a narrative from JSON
3. **Build Your Story**:
   - Add events (map, 3D model, or slide)
   - Link events with semantic relationships
   - Add entities and organize by class

### Main Workflow

1. **Home Page** (`SMBVT_2.0.html`)
   - View your narratives
   - Create new narratives from Wikidata subjects
   - Import narratives from JSON
   - Manage user account

2. **Builder Interface** (`toolNew.html`)
   - Edit narrative title and cover image
   - Add new events (Map, 3D Model, Slide)
   - Save and manage events
   - Preview map visualization
   - Export narratives (OWL/JSON)
   - Publish narrative graphs

3. **Entity Management**
   - Browse entities by class
   - Search entities
   - Add new entities
   - Link entities to events

### Event Types

- **Map Events**: Geospatial events with coordinates and polygons
- **3D Model Events**: Integration with Sketchfab and Aton viewers
- **Slide Events**: Image-based presentations with captions

## Development

### Code Structure

```
remote-site/
├── assets/
│   └── fonts/
│       └── the-librarian/
│           ├── SourceSans3VF-*.ttf.woff2
│           └── SourceSerif4Variable-Roman.ttf.woff2
├── HTML/
│   ├── aton-event-viewer.html
│   ├── drawPolygons.html
│   ├── searchCoordinates.html
│   └── sketchfab-event-viewer.html
├── img/
│   ├── Narratia_logo.png
│   ├── aimh-lab.png
│   ├── modelIDExample.png
│   └── sketchfab.png
├── lib/
│   ├── bootstrap*.css
│   ├── demo-narraMapsNew.*.js
│   ├── narra*.css
│   ├── narrreview-narratia-theme.css
│   └── ... (other libraries)
├── PHP/
│   ├── api_helpers.php
│   ├── deleteDb.php
│   ├── insertDb.php
│   ├── PgConn.php
│   ├── publishMap.php
│   ├── session.php
│   ├── triplify.php
│   └── ... (other PHP files)
├── Short_Guide/
│   └── Short Guide to use SMBVT.pdf
├── storymaps/
│   └── (user-generated narratives)
├── verticalstorymap3D/
│   ├── aton-viewer.html
│   ├── index.php
│   ├── previewStorymap.php
│   └── README-*.md
├── demo.html
├── SMBVT_2.0.html
├── toolDemo.html
├── toolNew.html
└── README.md
```

### Styling

The tool uses a custom Narratia theme defined in `lib/narrreview-narratia-theme.css`:
- Clean, modern interface
- Consistent color scheme
- Responsive design
- Accessible UI elements

### Fonts

- **Source Sans 3**: Variable font for headings and interface elements
- **Source Serif 4**: Variable font for body text
- Both fonts are loaded as WOFF2 for optimal performance

## Configuration

### PHP Configuration

Edit `PHP/PgConn.php` to configure database connections:

```php
<?php
// Example configuration
$dbhost = 'localhost';
$dbport = '5432';
$dbname = 'smbvt';
$dbuser = 'smbvt_user';
$dbpass = 'password';
```

### Fuseki Configuration

Configure Fuseki to expose the "narratives" dataset at the endpoint:
```
http://your-server/narratives/query
```

## Troubleshooting

### Common Issues

1. **Database Connection Errors**
   - Verify PostgreSQL is running
   - Check connection settings in `PgConn.php`
   - Ensure user has proper permissions

2. **Session Expiration**
   - The tool has an active session heartbeat
   - Keep the page open to maintain session
   - Logout and login again if session expires

3. **Map Visualization Not Loading**
   - Ensure all required JavaScript libraries are loaded
   - Check browser console for errors
   - Verify geospatial data is properly formatted

4. **3D Model Viewer Issues**
   - For Sketchfab: ensure model URLs are correct
   - For Aton: verify proxy configuration in `aton-proxy.php`

### Browser Support

The tool works best with recent versions of:
- Google Chrome
- Apple Safari
- Mozilla Firefox

## Contributing

This project is developed by the AIMH-DHgroup. Contributions are welcome through GitHub:

```
https://github.com/AIMH-DHgroup/narreview
```

## License

© 2017–2025 [ISTI-CNR](http://isti.cnr.it)

The Librarian [Terms of use](https://dlnarratives.eu/terms_of_use.html).

Images from [Wikimedia Commons](https://commons.wikimedia.org), [CC-BY-SA 3.0](https://creativecommons.org/licenses/by-sa/3.0) license.

## Acknowledgments

This work has been supported by the Digital Library for Narratives project and related research initiatives.

---

For questions or support, contact: emanuele.lenzi@isti.cnr.it
