# Security Policy

## Reporting a Vulnerability

We take security seriously at FCS API. If you discover a security vulnerability, please report it responsibly.

### How to Report

1. **Do NOT** open a public GitHub issue for security vulnerabilities
2. Email us at: **support@fcsapi.com**
3. Include:
   - Description of the vulnerability
   - Steps to reproduce
   - Potential impact
   - Any suggested fixes (optional)

### What to Expect

- **Response Time:** We aim to respond within 48 hours
- **Updates:** We will keep you informed of our progress
- **Credit:** We will credit you in our security acknowledgments (if desired)

## Supported Versions

| Version | Supported          |
| ------- | ------------------ |
| Latest  | :white_check_mark: |
| Older   | :x:                |

We recommend always using the latest version of our libraries.

## Security Best Practices

When using FCS API libraries:

1. **Never expose your API key** in client-side code
2. **Use token-based authentication** for frontend applications
3. **Set appropriate token expiry times** (shorter is more secure)
4. **Store API keys securely** using environment variables
5. **Keep libraries updated** to the latest version

## Token Authentication

For client-side applications, use our secure token system:
- Generate tokens on your backend server
- Use `_public_key`, `_expiry`, and `_token` parameters
- See [Token Generator Examples](https://github.com/fcsapi/token-generator)

## More Information

- [API Documentation](https://fcsapi.com/document/forex-api)
- [Dashboard](https://fcsapi.com/dashboard) - Manage your API keys

Thank you for helping keep FCS API secure!
