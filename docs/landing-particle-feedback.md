# Landing Particle Animation – Feedback & Improvements

Screenshots were taken of the landing page at `http://localhost:8000`. Formation could not be triggered via browser automation (hover refs were unavailable), so a **keyboard shortcut** was added: press **Space** or **F** to trigger the AES formation for easier testing and future screenshots.

---

## What’s Working Well

- **Black background** gives strong contrast for the gold particles.
- **Floating motion** (sine-based drift) makes the idle state feel alive.
- **Single “Continue”** appears after first interaction, keeping the flow clear.
- **Bold “AES”** with bevel and larger point size should read clearly when formed.

---

## Suggested Improvements

### 1. **Make the trigger obvious (optional)**
- Right now formation only starts on mouse/touch (or Space/F). Consider a very subtle hint on first load, e.g. “Move or tap to reveal” that fades after 2–3 seconds, or a brief glow/pulse so users know the scene is interactive.

### 2. **Stronger “fully formed” state**
- When particles are close to their targets, **snap or tighten** the lerp so the final frame is crisp (e.g. if distance to target < 0.1, set position = target).
- Optionally **slightly increase particle size** (e.g. 0.12 → 0.14) only when `forming` is true, so the letters look bolder when fully formed (would require two materials or a uniform).

### 3. **Idle state variety**
- **FLOAT_SPEED** is slow; consider a gentle variation per particle (e.g. multiply by 0.8–1.2) so the motion feels less uniform.
- Optionally add a very slow **global phase** (e.g. `time * 0.0002`) so the whole field subtly drifts over time.

### 4. **Scatter transition**
- When returning to idle (after POINTER_IDLE_MS), particles lerp back to `seaPositions`. Consider **adding a short burst of random velocity** so the “break apart” feels more dynamic before they settle into floating.

### 5. **Performance / accessibility**
- **IntersectionObserver** already pauses when off-screen; keep it.
- If needed on low-end devices, **reduce PARTICLE_COUNT** (e.g. 3000) when `window.devicePixelRatio > 1` or when frame rate drops, or use a media query / capability check.

### 6. **Continue button**
- The button appears after first formation. Optionally **fade it in only after formation is mostly complete** (e.g. when average distance to target < threshold for one frame) so the CTA doesn’t compete with mid-animation.

### 7. **Letter clarity at distance**
- Camera is at z = 25; the “AES” geometry is scaled to fit. If the wordmark still feels small on large screens, **scale the text size by aspect ratio** or use a slightly larger base size (e.g. 18) and ensure the bounding box stays within the camera frustum.

---

## Quick wins to try first

1. Add **snap-to-target** when particles are within a small distance of their formation target.
2. Add **Space/F** (done) for testing and screenshots.
3. Slightly **increase LERP_FORM** (e.g. 0.1) so formation completes faster and the final “AES” frame is visible sooner.
4. Add **per-particle FLOAT_SPEED variation** for more natural idle motion.
