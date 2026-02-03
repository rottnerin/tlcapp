/**
 * TLC Landing: yellow dots form the letters AES large and centered on cursor/touch.
 * Uses canvas-based text sampling for clear, filled letter formation.
 */

import * as THREE from 'three';

const GOLD = 0xf4d35e;
const BLACK = 0x000000;
const PARTICLE_COUNT = 6000;
const LERP_FORM = 0.04;
const LERP_SEA = 0.02;
const POINTER_IDLE_MS = 3000;
const FLOAT_AMPLITUDE = 1.5;
const FLOAT_SPEED = 0.0005;
const SHOW_BUTTON_DELAY = 1800;

let scene, camera, renderer, points, geometry;
let positions, seaPositions, targetPositions;
let forming = false;
let formingStartedAt = 0;
let pointerActiveAt = 0;
let observer;
let rafId;
let floatPhases;
let floatSpeeds;
let buttonShown = false;

function getContainer() {
    return document.getElementById('landing-scene');
}

function init() {
    const container = getContainer();
    if (!container) return;

    scene = new THREE.Scene();
    scene.background = new THREE.Color(BLACK);

    const aspect = window.innerWidth / window.innerHeight;
    camera = new THREE.PerspectiveCamera(60, aspect, 0.1, 1000);
    camera.position.z = 18;

    renderer = new THREE.WebGLRenderer({ antialias: true, alpha: false });
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    container.appendChild(renderer.domElement);

    // Sea positions: random spread (base positions for floating)
    seaPositions = new Float32Array(PARTICLE_COUNT * 3);
    floatPhases = new Float32Array(PARTICLE_COUNT * 3);
    floatSpeeds = new Float32Array(PARTICLE_COUNT);
    const spread = 25;
    for (let i = 0; i < PARTICLE_COUNT; i++) {
        const i3 = i * 3;
        seaPositions[i3] = (Math.random() - 0.5) * 2 * spread;
        seaPositions[i3 + 1] = (Math.random() - 0.5) * 2 * spread * 0.6;
        seaPositions[i3 + 2] = (Math.random() - 0.5) * spread * 0.25;
        floatPhases[i3] = Math.random() * Math.PI * 2;
        floatPhases[i3 + 1] = Math.random() * Math.PI * 2;
        floatPhases[i3 + 2] = Math.random() * Math.PI * 2;
        floatSpeeds[i] = 0.7 + Math.random() * 0.6;
    }

    // Current positions (copy of sea initially)
    positions = new Float32Array(seaPositions.length);
    positions.set(seaPositions);

    geometry = new THREE.BufferGeometry();
    geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
    geometry.attributes.position.needsUpdate = true;

    const material = new THREE.PointsMaterial({
        color: GOLD,
        size: 0.07,
        sizeAttenuation: true,
        transparent: true,
        opacity: 0.95,
    });

    points = new THREE.Points(geometry, material);
    scene.add(points);

    // Load formation targets from canvas-rendered text
    targetPositions = buildAESTargets();
    setupPointerListeners();
    setupResize();
    setupVisibilityPause();
    animate();
}

/**
 * Shuffle array in place using Fisher-Yates algorithm
 */
function shuffle(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
    return array;
}

/**
 * Render "AES" to a canvas and sample filled pixels to create evenly distributed target points.
 */
function buildAESTargets() {
    const canvas = document.createElement('canvas');
    const width = 600;
    const height = 280;
    canvas.width = width;
    canvas.height = height;
    const ctx = canvas.getContext('2d');

    // Clear and draw text
    ctx.fillStyle = '#000';
    ctx.fillRect(0, 0, width, height);
    ctx.fillStyle = '#fff';
    ctx.font = 'bold 180px Arial, Helvetica, sans-serif';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillText('AES', width / 2, height / 2);

    // Sample white pixels
    const imageData = ctx.getImageData(0, 0, width, height);
    const data = imageData.data;
    const filledPixels = [];

    for (let y = 0; y < height; y++) {
        for (let x = 0; x < width; x++) {
            const i = (y * width + x) * 4;
            if (data[i] > 128) {
                filledPixels.push({ x, y });
            }
        }
    }

    // Shuffle pixels for even distribution
    shuffle(filledPixels);

    // Map to 3D coordinates centered at origin
    const scale = 16 / width;
    const out = [];

    for (let i = 0; i < PARTICLE_COUNT; i++) {
        const p = filledPixels[i % filledPixels.length];
        // Add small random offset so particles don't stack exactly
        const jitterX = (Math.random() - 0.5) * 0.03;
        const jitterY = (Math.random() - 0.5) * 0.03;
        const x = (p.x - width / 2) * scale + jitterX;
        const y = (height / 2 - p.y) * scale + jitterY;
        const z = (Math.random() - 0.5) * 0.1;
        out.push(new THREE.Vector3(x, y, z));
    }

    return out;
}

function showButton() {
    if (!buttonShown) {
        buttonShown = true;
        document.body.classList.add('landing-formed');
    }
}

function setupPointerListeners() {
    const triggerForm = () => {
        pointerActiveAt = Date.now();
        if (!forming) {
            forming = true;
            formingStartedAt = Date.now();
        }
    };

    const checkIdle = () => {
        if (Date.now() - pointerActiveAt > POINTER_IDLE_MS) {
            forming = false;
        }
        // Show button after delay once forming started
        if (formingStartedAt > 0 && !buttonShown && Date.now() - formingStartedAt > SHOW_BUTTON_DELAY) {
            showButton();
        }
    };

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        forming = false;
        return;
    }

    window.addEventListener('mousemove', triggerForm);
    window.addEventListener('mousedown', triggerForm);
    window.addEventListener('touchstart', triggerForm, { passive: true });
    window.addEventListener('touchmove', triggerForm, { passive: true });
    window.addEventListener('keydown', (e) => {
        if (e.key === ' ' || e.key === 'f' || e.key === 'F') {
            e.preventDefault();
            triggerForm();
        }
    });
    setInterval(checkIdle, 100);
}

function setupResize() {
    window.addEventListener('resize', () => {
        if (!camera || !renderer) return;
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
    });
}

function setupVisibilityPause() {
    observer = new IntersectionObserver(
        (entries) => {
            const visible = entries[0]?.isIntersecting ?? true;
            if (!visible && rafId) {
                cancelAnimationFrame(rafId);
                rafId = 0;
            } else if (visible && !rafId) {
                animate();
            }
        },
        { threshold: 0 }
    );
    const container = getContainer();
    if (container) observer.observe(container);
}

function animate() {
    if (!geometry?.attributes?.position || !targetPositions) {
        rafId = requestAnimationFrame(animate);
        return;
    }

    const posAttr = geometry.attributes.position;
    const time = Date.now();

    for (let i = 0; i < PARTICLE_COUNT; i++) {
        const i3 = i * 3;
        if (forming) {
            const tx = targetPositions[i].x;
            const ty = targetPositions[i].y;
            const tz = targetPositions[i].z;
            const dx = tx - positions[i3];
            const dy = ty - positions[i3 + 1];
            const dz = tz - positions[i3 + 2];
            const dist = Math.sqrt(dx * dx + dy * dy + dz * dz);
            
            // Snap when very close for crisp final state
            if (dist < 0.03) {
                positions[i3] = tx;
                positions[i3 + 1] = ty;
                positions[i3 + 2] = tz;
            } else {
                positions[i3] += dx * LERP_FORM;
                positions[i3 + 1] += dy * LERP_FORM;
                positions[i3 + 2] += dz * LERP_FORM;
            }
        } else {
            const speed = floatSpeeds[i];
            const px = seaPositions[i3] + Math.sin(time * FLOAT_SPEED * speed + floatPhases[i3]) * FLOAT_AMPLITUDE;
            const py = seaPositions[i3 + 1] + Math.sin(time * FLOAT_SPEED * speed * 1.1 + floatPhases[i3 + 1]) * FLOAT_AMPLITUDE;
            const pz = seaPositions[i3 + 2] + Math.sin(time * FLOAT_SPEED * speed * 0.9 + floatPhases[i3 + 2]) * FLOAT_AMPLITUDE * 0.4;
            positions[i3] += (px - positions[i3]) * LERP_SEA;
            positions[i3 + 1] += (py - positions[i3 + 1]) * LERP_SEA;
            positions[i3 + 2] += (pz - positions[i3 + 2]) * LERP_SEA;
        }
    }

    posAttr.needsUpdate = true;
    renderer.render(scene, camera);
    rafId = requestAnimationFrame(animate);
}

init();
