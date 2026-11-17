# Contributing to SMBVT

Thank you for your interest in contributing to the Story Map Building and Visualization Tool (SMBVT)!

## How to Contribute

### Reporting Issues

1. **Check existing issues** on [GitHub Issues](https://github.com/AIMH-DHgroup/smbvt/issues) to see if your problem or feature request has already been reported.

2. **Create a new issue** with:
   - Clear, descriptive title
   - Steps to reproduce the issue
   - Expected vs. actual behavior
   - Browser and OS information
   - Screenshots if applicable

### Submitting Pull Requests

1. **Fork the repository** on GitHub

2. **Clone your fork locally**:
   ```bash
   git clone https://github.com/your-username/smbvt.git
   cd smbvt
   ```

3. **Create a branch** for your changes:
   ```bash
   git checkout -b your-branch-name
   ```

4. **Make your changes** following the existing code style and patterns

5. **Test your changes**:
   - Verify all functionality works as expected
   - Check for JavaScript errors in browser console
   - Test across different browsers

6. **Commit your changes** with descriptive messages:
   ```bash
   git commit -m "Fix: description of the change"
   ```

7. **Push to your fork**:
   ```bash
   git push origin your-branch-name
   ```

8. **Create a Pull Request** on GitHub targeting the `dev-1` branch

## Development Guidelines

### Code Structure

- **Frontend**: HTML, CSS, and JavaScript in the root directory
- **Backend**: PHP files in the `PHP/` directory
- **Libraries**: Third-party libraries in `lib/` directory
- **Assets**: Fonts, images in `assets/` and `img/` directories

### JavaScript Modules

The tool uses a modular JavaScript architecture:

- `demo-narraMapsNew.ui.js`: UI interactions and event handling
- `demo-narraMapsNew.map.js`: Map-related functionality
- `demo-narraMapsNew.wikidata.js`: Wikidata integration
- `demo-narraMapsNew.persistence.js`: Database operations
- `demo-narraMapsNew.sketchfab.js`: Sketchfab integration
- `demo-narraMapsNew.aton.js`: Aton viewer integration
- `demo-narraMapsNew.events.js`: Event management

### CSS Structure

- `narrreview-narratia-theme.css`: Main theme colors and variables
- `narraNew.css`: Layout and component styles
- `bootstrap*.css`: Bootstrap framework files

### PHP API

- Use `api_helpers.php` for common API functions
- Follow RESTful principles for endpoints
- Validate all user input
- Use prepared statements for database queries

## Testing

### Manual Testing

1. **Functional Testing**: Verify all features work as expected
2. **Cross-browser Testing**: Test in Chrome, Safari, and Firefox
3. **Responsive Testing**: Check on different screen sizes
4. **Accessibility Testing**: Verify keyboard navigation and screen reader compatibility

### Automated Testing

Currently, the project uses manual testing. Future improvements may include:
- Unit tests for JavaScript modules
- Integration tests for API endpoints
- End-to-end tests for user workflows

## Coding Standards

### JavaScript

- Use strict mode: `"use strict"`
- Follow ES6+ syntax where possible
- Use meaningful variable and function names
- Keep functions focused and single-purpose
- Add JSDoc comments for public functions

### PHP

- Use strict typing where supported
- Follow PSR-12 coding standards
- Validate all input
- Use prepared statements for SQL queries
- Keep functions focused and reusable

### CSS

- Use CSS variables for theme colors
- Follow BEM methodology for class names
- Keep styles modular and component-focused
- Use relative units (rem, %) for responsive design

## Documentation

### Updating Documentation

- Update `README.md` for major changes
- Add comments to complex code sections
- Document new API endpoints
- Update user guides in `Short_Guide/`

### Writing Good Commit Messages

- Use present tense: "Fix bug" not "Fixed bug"
- Be descriptive: explain what and why, not just what changed
- Reference issues: "Fix #123: description"
- Keep line length under 72 characters

## Branch Strategy

- `main`: Production-ready code
- `dev-1`: Development branch for new features
- Feature branches: Created from `dev-1`, merged back to `dev-1`

## Release Process

1. Code is merged to `dev-1` through pull requests
2. Regular integration and testing on `dev-1`
3. When ready, create a pull request from `dev-1` to `main`
4. Tag releases with semantic versioning (e.g., `v2.1.0`)

## Community Guidelines

- Be respectful and inclusive
- Assume positive intent
- Provide constructive feedback
- Follow the [Code of Conduct](CODE_OF_CONDUCT.md) (if applicable)

## Getting Help

For questions or support:
- Open an issue on GitHub
- Contact the maintainers: emanuele.lenzi@isti.cnr.it
- Check existing documentation and guides

---

Thank you for contributing to SMBVT!
