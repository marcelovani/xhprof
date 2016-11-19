<div id="options">
<?php echo get_home_button(); ?>
  <label id="engine">
    Engine:
    <select>
      <option>circo</option>
      <option selected>dot</option>
      <option>fdp</option>
      <option>neato</option>
      <option>osage</option>
      <option>twopi</option>
    </select>
  </label>

  <label id="format">
    Format:
    <select>
      <option selected>svg</option>
      <option>png</option>
      <option>xdot</option>
      <option>plain</option>
      <option>ps</option>
      <option>3D</option>
    </select>
  </label>

  <label id="raw">
    <input type="checkbox"> Show raw output
  </label>

  <?php echo get_show_internal_button('Show internal functions', 1); ?>
  <span class="threshold">Threshold
    <?php
    echo get_threshold_button('++', 0.1, $threshold);
    echo get_threshold_button('+', 0.01, $threshold);
    echo get_threshold_button('-', -0.01, $threshold);
    echo get_threshold_button('--', -0.1, $threshold);
    ?>
            </span>

</div>
