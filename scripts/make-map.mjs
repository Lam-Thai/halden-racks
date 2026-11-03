// Generates theme/halden/assets/images/map.svg: a stylised map of the fictional town of Port Alder,
// used by the location and finale blocks instead of a real aerial photo.
// Feature positions are percentages that match the location block's pins:
//   data centre 37/38, head office 73/27, Central Station 40/80.
//   node scripts/make-map.mjs
import fs from 'node:fs';

const W = 1780, H = 1000;
let seed = 7;
const rnd = () => ((seed = (seed * 16807) % 2147483647) - 1) / 2147483646;
const px = (p) => (p / 100) * W, py = (p) => (p / 100) * H;
const out = [];
const add = (s) => out.push(s);

// Palette: the theme's warm night surfaces + lake blue.
const C = { ground: '#1A1412', block: '#251D1A', blockHi: '#2E2420', edge: '#3A2F2A', road: '#120E0C', major: '#0E0B0A', lake: '#0B2F3B', lakeEdge: '#1E7F9C', park: '#1C2520', rail: '#4A3F3A' };

add(`<rect width="${W}" height="${H}" fill="${C.ground}"/>`);

// Lake along the bottom with a soft shoreline.
let shore = `M0 ${py(86)}`;
for (let x = 0; x <= W; x += 40) shore += ` L${x} ${(py(86) + Math.sin(x / 140) * 14 + Math.sin(x / 47) * 5).toFixed(1)}`;
add(`<path d="${shore} L${W} ${H} L0 ${H} Z" fill="${C.lake}"/>`);
add(`<path d="${shore}" fill="none" stroke="${C.lakeEdge}" stroke-opacity=".55" stroke-width="2"/>`);
for (let i = 0; i < 9; i++) {
  const y = py(90) + i * 11, x0 = rnd() * W * 0.8;
  add(`<path d="M${x0.toFixed(0)} ${y} h${(80 + rnd() * 220).toFixed(0)}" stroke="${C.lakeEdge}" stroke-opacity="${(0.12 + rnd() * 0.12).toFixed(2)}" stroke-width="1.5" stroke-linecap="round"/>`);
}

// City blocks on a grid, skipping the rail corridor and the shore.
const cols = [0, 7, 15, 22, 30, 41, 48, 56, 63, 71, 78, 86, 93, 100];
const rows = [0, 9, 17, 26, 34, 43, 51, 60, 67];
for (let i = 0; i < cols.length - 1; i++) {
  for (let j = 0; j < rows.length - 1; j++) {
    const x = px(cols[i]) + 10, y = py(rows[j]) + 10, w = px(cols[i + 1]) - px(cols[i]) - 20, h = py(rows[j + 1]) - py(rows[j]) - 20;
    if (w < 20 || h < 20) continue;
    const park = (i === 9 && j === 5) || (i === 2 && j === 6);
    add(`<rect x="${x.toFixed(0)}" y="${y.toFixed(0)}" width="${w.toFixed(0)}" height="${h.toFixed(0)}" rx="6" fill="${park ? C.park : C.block}" stroke="${C.edge}" stroke-opacity=".5"/>`);
    if (!park) {
      // Building footprints inside each block.
      const n = 1 + Math.floor(rnd() * 3);
      for (let k = 0; k < n; k++) {
        const bw = w * (0.25 + rnd() * 0.3), bh = h * (0.3 + rnd() * 0.35);
        const bx = x + 8 + rnd() * (w - bw - 16), by = y + 8 + rnd() * (h - bh - 16);
        add(`<rect x="${bx.toFixed(0)}" y="${by.toFixed(0)}" width="${bw.toFixed(0)}" height="${bh.toFixed(0)}" rx="3" fill="${C.blockHi}"/>`);
      }
    }
  }
}

// Major roads: a waterfront boulevard, one cross street, and a diagonal avenue.
add(`<path d="M0 ${py(70)} H${W}" stroke="${C.major}" stroke-width="26"/>`);
add(`<path d="M${px(30)} 0 V${py(86)}" stroke="${C.major}" stroke-width="22"/>`);
add(`<path d="M${px(48)} ${py(70)} L${px(100)} ${py(8)}" stroke="${C.major}" stroke-width="20"/>`);

// Rail corridor into Central Station.
add(`<path d="M0 ${py(77)} C${px(30)} ${py(76)} ${px(60)} ${py(78)} ${W} ${py(75)}" fill="none" stroke="${C.rail}" stroke-width="10"/>`);
add(`<path d="M0 ${py(77)} C${px(30)} ${py(76)} ${px(60)} ${py(78)} ${W} ${py(75)}" fill="none" stroke="${C.ground}" stroke-width="4" stroke-dasharray="2 14"/>`);
add(`<rect x="${px(35)}" y="${py(73.5)}" width="${px(10)}" height="${py(8)}" rx="6" fill="${C.blockHi}" stroke="${C.rail}" stroke-width="2"/>`);

// The two Halden buildings, lit so they read under the pins.
const lit = (cx, cy, w, h) => add(`<rect x="${(px(cx) - w / 2).toFixed(0)}" y="${(py(cy) - h / 2).toFixed(0)}" width="${w}" height="${h}" rx="6" fill="#3B2C26" stroke="#7A5A4E" stroke-width="2"/>`);
lit(37, 39, 170, 120); // data centre
lit(73, 28, 120, 110); // head office

// Street names in the theme's mono face (falls back to monospace inside an <img>).
const label = (x, y, t, r = 0) => add(`<text x="${px(x)}" y="${py(y)}" transform="rotate(${r} ${px(x)} ${py(y)})" font-family="JetBrains Mono, monospace" font-size="15" letter-spacing="3" fill="#80716C">${t}</text>`);
label(4, 68.6, 'FOUNDRY ROW');
label(30.9, 20, 'QUAY STREET', 90);
label(78, 40, 'ALDER AVENUE', -50);
label(70, 95, 'ALDER LAKE');

fs.writeFileSync('theme/halden/assets/images/map.svg', `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ${W} ${H}" width="${W}" height="${H}">${out.join('')}</svg>\n`);
console.log('map.svg', (fs.statSync('theme/halden/assets/images/map.svg').size / 1024).toFixed(1), 'KB');
