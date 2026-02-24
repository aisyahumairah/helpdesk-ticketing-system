# Google OAuth (SSO) for local development

Google OAuth **does not allow** `.test`, `.local`, or `.localhost` in **Authorized JavaScript origins** or **Authorized redirect URIs**. You cannot use `http://techdesk.test` in the Google Cloud Console.

Use one of these approaches for local development.

---

## Option 1: Use localhost (simplest)

1. **Google Cloud Console**  
   In your OAuth 2.0 Client (APIs & Services → Credentials → your OAuth client):
   - **Authorized JavaScript origins:** `http://localhost`
   - **Authorized redirect URIs:** `http://localhost/auth/google/callback`  
   If you use a port (e.g. `php artisan serve`):  
   `http://localhost:8000` and `http://localhost:8000/auth/google/callback`.

2. **Laravel `.env`**
   - `APP_URL=http://localhost` (or `http://localhost:8000` if using artisan serve).
   - Remove `GOOGLE_REDIRECT_URL` or leave it empty so the callback is built from `APP_URL`.

3. **Access the app at that URL**  
   - Either point Laragon so this project is served at `http://localhost` (e.g. make it the default site or add a vhost for localhost),  
   - Or run `php artisan serve` and use `http://localhost:8000` (and the same in Google + `APP_URL`).

After that, open the app at **http://localhost** (or **http://localhost:8000**), click “Login with Google”, and the redirect will work.

---

## Option 2: Use a tunnel (e.g. ngrok) to keep using techdesk.test

If you want to keep opening the app as `http://techdesk.test` but still need a valid redirect for Google:

1. **Install and run ngrok** (or similar):
   ```bash
   ngrok http 80
   ```
   Use the port Laragon uses (e.g. 80). You’ll get a URL like `https://abc123.ngrok-free.app`.

2. **Google Cloud Console**
   - **Authorized JavaScript origins:** `https://abc123.ngrok-free.app`
   - **Authorized redirect URIs:** `https://abc123.ngrok-free.app/auth/google/callback`

3. **Laravel `.env`** (only when testing Google login via ngrok):
   - `APP_URL=https://abc123.ngrok-free.app`
   - Leave `GOOGLE_REDIRECT_URL` empty.

4. **Test**  
   Open the app via the **ngrok URL** (e.g. `https://abc123.ngrok-free.app`), then click “Login with Google”.  
   Note: ngrok URLs change on free tier unless you use a reserved domain.

---

## Summary

| You want to open the app at | Use in Google Console | Set in .env |
|-----------------------------|------------------------|-------------|
| `http://localhost` or `http://localhost:8000` | Same origin + `/auth/google/callback` | `APP_URL=http://localhost` (or `:8000`) |
| `https://xxxx.ngrok-free.app` | Same origin + `/auth/google/callback` | `APP_URL=https://xxxx.ngrok-free.app` |
| `http://techdesk.test` | **Not allowed** by Google | — |

The callback URL is built as **`APP_URL` + `/auth/google/callback`** when `GOOGLE_REDIRECT_URL` is not set.
