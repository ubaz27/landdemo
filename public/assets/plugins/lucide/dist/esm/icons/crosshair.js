/**
 * @license lucide v0.323.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */

import defaultAttributes from '../defaultAttributes.js';

const Crosshair = [
  "svg",
  defaultAttributes,
  [
    ["circle", { cx: "12", cy: "12", r: "10" }],
    ["line", { x1: "22", x2: "18", y1: "12", y2: "12" }],
    ["line", { x1: "6", x2: "2", y1: "12", y2: "12" }],
    ["line", { x1: "12", x2: "12", y1: "6", y2: "2" }],
    ["line", { x1: "12", x2: "12", y1: "22", y2: "18" }]
  ]
];

export { Crosshair as default };
//# sourceMappingURL=crosshair.js.map
