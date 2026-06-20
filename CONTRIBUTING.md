# Contributing to BrickLogic

Thank you for your interest in contributing to BrickLogic! We welcome contributions from developers, designers, and anyone passionate about improving construction planning tools.

## Code of Conduct

Please be respectful, inclusive, and professional in all interactions. We aim to create a welcoming community for everyone.

## Getting Started

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- XAMPP or similar local development environment
- Git and GitHub account
- Basic knowledge of PHP, JavaScript, MySQL

### Fork and Clone
1. Fork the repository on GitHub
2. Clone your fork locally:
   ```bash
   git clone https://github.com/YOUR_USERNAME/bricklogic.git
   cd bricklogic
   ```
3. Add upstream remote:
   ```bash
   git remote add upstream https://github.com/laylfighter/bricklogic.git
   ```

### Set Up Development Environment
1. Place project in `C:\Xampp\htdocs\bricklogic\`
2. Create `.env` file from `.env.example`
3. Update database credentials in `config.php`
4. Import `bricklogic.sql` into MySQL
5. Start XAMPP (Apache + MySQL)
6. Access at `http://localhost/bricklogic`

## Development Workflow

### Create a Feature Branch
```bash
git checkout -b feature/your-feature-name
```

Use clear, descriptive branch names:
- `feature/design-editor-improvements`
- `fix/budget-calculation-bug`
- `docs/api-documentation`

### Make Your Changes
1. Write clean, readable code
2. Follow existing code style and conventions
3. Test your changes thoroughly
4. Commit with clear, descriptive messages:
   ```bash
   git commit -m "Add feature: budget estimation improvements"
   ```

### Code Standards

#### PHP
- Follow PSR-12 coding standards
- Use meaningful variable names
- Add comments for complex logic
- Use prepared statements for SQL queries
- Implement input validation and sanitization

#### JavaScript
- Use ES6+ syntax where possible
- Avoid global variables
- Use const/let instead of var
- Add JSDoc comments for functions

#### SQL
- Use meaningful table and column names
- Maintain proper indexing for performance
- Document complex queries
- Use transactions for data integrity

### Testing

Before submitting a pull request:
1. Test all new features locally
2. Verify no existing functionality is broken
3. Test on different browsers (Chrome, Firefox, Safari)
4. Test on different screen sizes (responsive design)
5. Check for SQL injection vulnerabilities
6. Validate CSRF token handling

## Submitting Changes

### Push to Your Fork
```bash
git push origin feature/your-feature-name
```

### Create a Pull Request
1. Go to the original repository on GitHub
2. Click "New Pull Request"
3. Select your branch to compare
4. Provide a clear title and description:
   - What changes were made?
   - Why are these changes needed?
   - How can reviewers test the changes?

### PR Description Template
```markdown
## Description
Brief description of the changes

## Type of Change
- [ ] New feature
- [ ] Bug fix
- [ ] Documentation update
- [ ] Performance improvement

## Changes Made
- Change 1
- Change 2

## How to Test
1. Step 1
2. Step 2

## Screenshots (if applicable)
[Add screenshots]

## Checklist
- [ ] Code follows style guidelines
- [ ] Self-reviewed the code
- [ ] Comments added for complex logic
- [ ] Documentation updated
- [ ] No new warnings generated
- [ ] Tested on different browsers
- [ ] SQL changes are optimized
```

## Reporting Bugs

Found a bug? Please report it!

1. Check if the bug is already reported in Issues
2. Provide a clear, descriptive title
3. Include:
   - Description of the bug
   - Steps to reproduce
   - Expected behavior
   - Actual behavior
   - Browser and OS information
   - Screenshots if applicable

## Requesting Features

Have a feature idea?

1. Check existing issues and discussions
2. Provide a clear description of the feature
3. Explain the use case and benefits
4. Include mockups or examples if helpful

## Documentation

Help improve our documentation!

- Fix typos and unclear explanations
- Add examples and use cases
- Improve API documentation
- Create tutorials

## Project Structure

Understanding the project layout helps with contributions:

```
bricklogic/
├── css/              # Stylesheets
├── js/               # JavaScript files
├── php/              # Backend logic
│   ├── auth/         # Authentication
│   ├── admin/        # Admin functions
│   ├── user/         # User functions
│   └── supplier/     # Supplier functions
├── design/           # SVG design assets
├── Uploads/          # User uploads
└── config.php        # Configuration
```

## Commit Message Guidelines

Write clear, descriptive commit messages:

```
[type]: Brief description

Optional longer explanation

- Bullet points for changes
- Keep lines under 72 characters
```

**Types:**
- `feat:` New feature
- `fix:` Bug fix
- `docs:` Documentation
- `style:` Code style (no logic change)
- `refactor:` Code refactoring
- `test:` Adding tests
- `chore:` Build, dependencies, etc.

### Examples:
```
feat: Add material recommendation based on location
fix: Resolve budget calculation for multiple floors
docs: Update API documentation
refactor: Optimize database queries for order retrieval
```

## Review Process

1. A maintainer will review your PR
2. Changes may be requested
3. Update your PR based on feedback
4. Once approved, your PR will be merged
5. Your contribution will be credited!

## Questions or Need Help?

- Check existing Issues and Discussions
- Comment on relevant Issues
- Contact the maintainers
- Join our community

## License

By contributing, you agree that your contributions will be licensed under the same license as the project.

---

**Thank you for contributing to BrickLogic!** 🎉

Your contributions make our construction planning platform better for everyone.
