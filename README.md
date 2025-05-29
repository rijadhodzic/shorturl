# Secure Short URL Generator

A lightweight and secure PHP-based URL shortener with support for:

- Password-protected links
- Expiration dates (up to 31 days)
- 10-second redirect countdown
- Ad placement support
- XSS and CSRF protection
- No database required (JSON-based)
- Responsive Bootstrap 5 dark design

## ✍️ Author

**Rijad Hodzic**  
[https://backlinkexchange.org](https://backlinkexchange.org)

---

## 🚀 Features

- Create short URLs quickly
- Optionally protect URLs with a password
- Set expiration for short links (auto-deletes expired ones)
- Countdown timer before redirection (10 seconds)
- Built-in space for advertising
- HTML, Forum, and Direct link outputs
- Dark-mode responsive design

---

## 📂 Installation

1. Upload the contents to your PHP-enabled web server.
2. Ensure the webserver has write access to the `/json/` folder.
3. Open `index.php` in your browser to begin shortening links.

---

## 🛡 Security

- CSRF tokens for form protection
- `password_hash()` and `password_verify()` for secure passwords
- URL validation using `FILTER_VALIDATE_URL`
- Directory traversal and brute-force protection

---

## 💰 Ad Setup

To display ads during the countdown:

1. Open `redirect.php`
2. Find the section marked:

```html
<!-- INSERT YOUR AD CODE BELOW -->
```

3. Replace that area with your ad code (e.g., Google AdSense, PropellerAds, etc.)

```html
<script async src="https://example-adnetwork.com/script.js"></script>
<ins class="adsbyexample"></ins>
<script>(adsbyexample = window.adsbyexample || []).push({});</script>
```

4. Ads will appear centered in the countdown page.

---

## 🔗 Output Formats

When a short URL is created, the following formats are shown:

- Direct Link  
- HTML Link: `<a href="...">...</a>`
- Forum Link: `[url=...]...[/url]`

---

## 📝 License

MIT License — free for personal and commercial use.

---

## 🌐 Credits

Made with ❤️ by Rijad Hodzic  
Visit: [https://backlinkexchange.org](https://backlinkexchange.org)
