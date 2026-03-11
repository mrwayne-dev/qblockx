/* ============================================================
   ArqoraCapital — LightRays WebGL Effect
   Vanilla JS port of OGL/React LightRays component.
   Usage: window.LightRays.init(container, options) → { destroy() }
   ============================================================ */

(function (global) {
  'use strict';

  /* ── Vertex Shader ─────────────────────────────────────────── */
  var VERT = [
    'attribute vec2 position;',
    'void main() {',
    '  gl_Position = vec4(position, 0.0, 1.0);',
    '}'
  ].join('\n');

  /* ── Fragment Shader ───────────────────────────────────────── */
  var FRAG = [
    'precision mediump float;',

    'uniform float iTime;',
    'uniform vec2  iResolution;',
    'uniform vec2  rayPos;',
    'uniform float lightSpread;',
    'uniform float rayLength;',
    'uniform vec3  raysColor;',
    'uniform vec2  mousePos;',
    'uniform float mouseInfluence;',
    'uniform float noiseAmount;',
    'uniform float distortion;',

    /* ── Pseudo-random noise ── */
    'float hash(vec2 p) {',
    '  return fract(sin(dot(p, vec2(127.1, 311.7))) * 43758.5453123);',
    '}',

    'float noise(vec2 p) {',
    '  vec2 i = floor(p);',
    '  vec2 f = fract(p);',
    '  vec2 u = f * f * (3.0 - 2.0 * f);',
    '  return mix(',
    '    mix(hash(i + vec2(0,0)), hash(i + vec2(1,0)), u.x),',
    '    mix(hash(i + vec2(0,1)), hash(i + vec2(1,1)), u.x),',
    '    u.y',
    '  );',
    '}',

    /* ── Single ray strength ── */
    /* origin: normalised source position (0-1)                   */
    /* dir:    unit direction of the ray                           */
    /* uv:     current fragment in 0-1 space                      */
    'float rayStrength(vec2 uv, vec2 origin, vec2 dir, float spread, float len) {',
    '  vec2 toFrag = uv - origin;',
    '  float proj  = dot(toFrag, dir);',               /* distance along ray */
    '  float perp  = length(toFrag - proj * dir);',    /* distance off axis  */
    /* only in forward direction and within length */
    '  if (proj < 0.0 || proj > len) return 0.0;',
    /* cone falloff: wide at source, narrows with distance */
    '  float cone = spread * (1.0 - proj / len);',
    '  float edge = smoothstep(cone, cone * 0.4, perp);',
    /* fade near source and at tip */
    '  float fade = smoothstep(0.0, 0.12, proj) * smoothstep(len, len * 0.6, proj);',
    '  return edge * fade;',
    '}',

    'void main() {',
    '  vec2 uv = gl_FragCoord.xy / iResolution;',
    /* WebGL y=0 is bottom; flip to match CSS coords (y=0 top) */
    '  uv.y = 1.0 - uv.y;',

    /* Apply subtle UV distortion */
    '  vec2 distUV = uv;',
    '  distUV.x += sin(uv.y * 8.0 + iTime * 0.4) * distortion * 0.5;',
    '  distUV.y += cos(uv.x * 6.0 + iTime * 0.3) * distortion * 0.3;',

    /* Mouse influence: nudge ray origin toward mouse */
    '  vec2 origin = mix(rayPos, mousePos, mouseInfluence * 0.35);',

    /* Add small time-based wobble to origin */
    '  origin.x += sin(iTime * 0.5) * 0.008;',
    '  origin.y += cos(iTime * 0.4) * 0.005;',

    '  float total = 0.0;',

    /* Cast N rays in a spread fan */
    '  const int NUM_RAYS = 7;',
    '  for (int i = 0; i < NUM_RAYS; i++) {',
    '    float t   = float(i) / float(NUM_RAYS - 1);',
    /* Spread angle: -lightSpread/2 to +lightSpread/2 in radians */
    '    float ang = (t - 0.5) * lightSpread * 3.14159265;',
    /* Base direction: downward (positive Y in flipped space) */
    '    float cosA = cos(ang);',
    '    float sinA = sin(ang);',
    /* Rotate a downward base vector [0,1] by ang */
    '    vec2 dir = vec2(sinA, cosA);',
    /* Slight per-ray time animation */
    '    float pulse = 0.85 + 0.15 * sin(iTime * 0.7 + t * 6.28);',
    '    float len   = rayLength * pulse;',

    /* Noise per ray adds organic variation */
    '    float n = noise(vec2(t * 5.0 + iTime * 0.2, iTime * 0.1));',
    '    float noiseFactor = 1.0 - noiseAmount + noiseAmount * n;',

    /* Mouse proximity brightens rays nearer mouse */
    '    float mouseDist = length(mousePos - origin);',
    '    float mBoost = 1.0 + mouseInfluence * (1.0 - smoothstep(0.0, 0.6, mouseDist));',

    '    total += rayStrength(distUV, origin, dir, lightSpread * (0.5 + t * 0.5), len)',
    '             * noiseFactor * mBoost;',
    '  }',

    '  total = clamp(total / float(NUM_RAYS), 0.0, 1.0);',
    '  total = pow(total, 0.7);', /* gamma lift for visible rays */

    '  vec3  col   = raysColor * total;',
    '  float alpha = total * 0.75;',

    '  gl_FragColor = vec4(col, alpha);',
    '}'
  ].join('\n');

  /* ── Helpers ───────────────────────────────────────────────── */
  function lerp(a, b, t) { return a + (b - a) * t; }

  function compileShader(gl, type, src) {
    var shader = gl.createShader(type);
    gl.shaderSource(shader, src);
    gl.compileShader(shader);
    if (!gl.getShaderParameter(shader, gl.COMPILE_STATUS)) {
      console.warn('[LightRays] Shader compile error:', gl.getShaderInfoLog(shader));
      gl.deleteShader(shader);
      return null;
    }
    return shader;
  }

  function createProgram(gl, vertSrc, fragSrc) {
    var vert = compileShader(gl, gl.VERTEX_SHADER, vertSrc);
    var frag = compileShader(gl, gl.FRAGMENT_SHADER, fragSrc);
    if (!vert || !frag) return null;
    var prog = gl.createProgram();
    gl.attachShader(prog, vert);
    gl.attachShader(prog, frag);
    gl.linkProgram(prog);
    if (!gl.getProgramParameter(prog, gl.LINK_STATUS)) {
      console.warn('[LightRays] Program link error:', gl.getProgramInfoLog(prog));
      return null;
    }
    gl.deleteShader(vert);
    gl.deleteShader(frag);
    return prog;
  }

  /* ── Main init function ────────────────────────────────────── */
  function init(container, userOpts) {
    /* Merge options with defaults */
    var opts = {
      rayPos:         [0.5, 0.0],
      lightSpread:    0.4,
      rayLength:      0.9,
      raysColor:      [0.15, 0.38, 0.92],
      mouseInfluence: 0.1,
      noiseAmount:    0.08,
      distortion:     0.04,
      opacity:        0.18
    };
    if (userOpts) {
      for (var k in userOpts) {
        if (Object.prototype.hasOwnProperty.call(userOpts, k)) {
          opts[k] = userOpts[k];
        }
      }
    }

    /* ── Create canvas ── */
    var canvas = document.createElement('canvas');
    canvas.style.cssText = [
      'position:absolute',
      'inset:0',
      'width:100%',
      'height:100%',
      'pointer-events:none',
      'z-index:0',
      'opacity:' + opts.opacity
    ].join(';');
    container.appendChild(canvas);

    /* ── WebGL context ── */
    var gl = canvas.getContext('webgl') ||
             canvas.getContext('experimental-webgl');
    if (!gl) {
      console.warn('[LightRays] WebGL not supported — effect skipped.');
      container.removeChild(canvas);
      return { destroy: function () {} };
    }

    /* ── Shader program ── */
    var program = createProgram(gl, VERT, FRAG);
    if (!program) {
      container.removeChild(canvas);
      return { destroy: function () {} };
    }
    gl.useProgram(program);

    /* ── Full-screen triangle ── */
    var verts = new Float32Array([-1, -1,  3, -1,  -1, 3]);
    var buf   = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, buf);
    gl.bufferData(gl.ARRAY_BUFFER, verts, gl.STATIC_DRAW);

    var posLoc = gl.getAttribLocation(program, 'position');
    gl.enableVertexAttribArray(posLoc);
    gl.vertexAttribPointer(posLoc, 2, gl.FLOAT, false, 0, 0);

    /* ── Uniform locations ── */
    var uTime     = gl.getUniformLocation(program, 'iTime');
    var uRes      = gl.getUniformLocation(program, 'iResolution');
    var uRayPos   = gl.getUniformLocation(program, 'rayPos');
    var uSpread   = gl.getUniformLocation(program, 'lightSpread');
    var uLen      = gl.getUniformLocation(program, 'rayLength');
    var uColor    = gl.getUniformLocation(program, 'raysColor');
    var uMouse    = gl.getUniformLocation(program, 'mousePos');
    var uMouseInf = gl.getUniformLocation(program, 'mouseInfluence');
    var uNoise    = gl.getUniformLocation(program, 'noiseAmount');
    var uDistort  = gl.getUniformLocation(program, 'distortion');

    /* ── Set static uniforms ── */
    gl.uniform2fv(uRayPos,   opts.rayPos);
    gl.uniform1f (uSpread,   opts.lightSpread);
    gl.uniform1f (uLen,      opts.rayLength);
    gl.uniform3fv(uColor,    opts.raysColor);
    gl.uniform1f (uMouseInf, opts.mouseInfluence);
    gl.uniform1f (uNoise,    opts.noiseAmount);
    gl.uniform1f (uDistort,  opts.distortion);

    /* ── Alpha blending ── */
    gl.enable(gl.BLEND);
    gl.blendFunc(gl.SRC_ALPHA, gl.ONE_MINUS_SRC_ALPHA);

    /* ── Resize handling ── */
    function resize() {
      var w = container.clientWidth  || window.innerWidth;
      var h = container.clientHeight || window.innerHeight;
      var dpr = Math.min(window.devicePixelRatio || 1, 2);
      canvas.width  = Math.round(w * dpr);
      canvas.height = Math.round(h * dpr);
      gl.viewport(0, 0, canvas.width, canvas.height);
      gl.uniform2f(uRes, canvas.width, canvas.height);
    }

    var ro = (typeof ResizeObserver !== 'undefined')
      ? new ResizeObserver(resize)
      : null;
    if (ro) { ro.observe(container); }
    else     { window.addEventListener('resize', resize); }
    resize();

    /* ── Mouse tracking (smooth lerp) ── */
    var targetMouse = opts.rayPos.slice();   /* start at rayPos */
    var currentMouse = opts.rayPos.slice();

    function onMouseMove(e) {
      targetMouse[0] = e.clientX / window.innerWidth;
      targetMouse[1] = e.clientY / window.innerHeight;
    }
    document.addEventListener('mousemove', onMouseMove, { passive: true });

    /* ── Render loop ── */
    var startTime = performance.now();
    var rafId;
    var destroyed = false;

    function render() {
      if (destroyed) return;
      rafId = requestAnimationFrame(render);

      /* Smooth mouse interpolation */
      currentMouse[0] = lerp(currentMouse[0], targetMouse[0], 0.06);
      currentMouse[1] = lerp(currentMouse[1], targetMouse[1], 0.06);

      var t = (performance.now() - startTime) * 0.001;
      gl.uniform1f(uTime, t);
      gl.uniform2f(uMouse, currentMouse[0], currentMouse[1]);

      gl.clearColor(0, 0, 0, 0);
      gl.clear(gl.COLOR_BUFFER_BIT);
      gl.drawArrays(gl.TRIANGLES, 0, 3);
    }

    rafId = requestAnimationFrame(render);

    /* ── Destroy / cleanup ── */
    function destroy() {
      destroyed = true;
      cancelAnimationFrame(rafId);
      document.removeEventListener('mousemove', onMouseMove);
      if (ro)   { ro.disconnect(); }
      else      { window.removeEventListener('resize', resize); }
      gl.deleteBuffer(buf);
      gl.deleteProgram(program);
      if (canvas.parentNode) { canvas.parentNode.removeChild(canvas); }
    }

    return { destroy: destroy };
  }

  /* ── Export ────────────────────────────────────────────────── */
  global.LightRays = { init: init };

}(typeof window !== 'undefined' ? window : this));
