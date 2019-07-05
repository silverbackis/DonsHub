<template>
  <a class="hamburger-link" @click="toggleMenu">
    <span class="is-sr-only">Menu</span>
    <svg
      xmlns="http://www.w3.org/2000/svg"
      viewBox="0 0 50 50"
      class="menu-svg"
    >
      <g class="end">
        <path class="end-one" d="M38,8 L 22,25.3 L 5,8" />
        <path class="end-two" d="M5,42 l 17-17.7 L 38,42" />
      </g>
      <g class="middle">
        <path class="middle-one" d="M5,8 c 0 0, 2.2 4.5, 12 13.5 S 38,8,38,8" />
        <path
          class="middle-two"
          d="M5,42 c 0 0, 2.2-4.5, 12-13.5 S 38,42,38,42"
        />
      </g>
      <g id="stage">
        <path class="stage-one" d="M5,8c0,0,2.2,0,13,0s13,0,27,0" />
        <path class="stage-two" d="M5,42c0,0,2.2,0,13,0s13,0,27,0" />
        <line class="stage-line" x1="5" y1="25" x2="45" y2="25" />
      </g>
    </svg>
  </a>
</template>

<script>
import { Back } from 'gsap'

export default {
  data() {
    return {
      timeline: null,
      open: false
    }
  },
  watch: {
    $route() {
      if (this.open) {
        this.toggleMenu()
      }
    }
  },
  mounted() {
    const cEase = this.$gsap.customEase.create(
      'custom',
      'M0,0,C0.033,0,0.026,0.204,0.146,0.216,0.294,0.23,0.294,-0.178,0.464,-0.178,0.616,-0.178,0.832,0.19,1,1'
    )

    // eslint-disable-next-line new-cap
    this.timeline = new this.$gsap.timeline({ paused: true, reversed: true })

    this.timeline.to(
      '.stage-one',
      0.3,
      { morphSVG: '.middle-one', ease: cEase },
      'start'
    )
    this.timeline.to(
      '.stage-two',
      0.3,
      { morphSVG: '.middle-two', ease: cEase },
      'start'
    )
    this.timeline.to(
      '.stage-line',
      0.3,
      { scaleX: 0, ease: Back.easeIn },
      'start'
    )

    this.timeline.to('.stage-one', 0.1, { morphSVG: '.end-one' }, 'end')
    this.timeline.to('.stage-two', 0.1, { morphSVG: '.end-two' }, 'end')
    this.$emit('toggle', this.open)
    this.$root.$on('menuClosed', this.menuClosed)
  },
  beforeDestroy() {
    this.$root.$off('menuClosed', this.menuClosed)
  },
  methods: {
    toggleMenu() {
      this.open = !this.open
      this.$emit('toggle', this.open)
      this.animateButton()
    },
    animateButton() {
      if (this.open) {
        this.timeline.play()
      } else {
        this.timeline.reverse()
      }
    },
    menuClosed() {
      this.open = false
      this.animateButton()
    }
  }
}
</script>

<style lang="sass">
@import 'assets/sass/utilities'
.hamburger-link
  position: relative
  display: inline-block
  z-index: 10
  height: 2rem
.menu-svg
  display: block
  position: relative
  fill: none
  stroke: $gold
  margin: 0 auto
  stroke-linecap: round
  stroke-width: 5px
  height: 100%
  cursor: pointer
  .stage-line
    transform-origin: 50% 50%
  .middle,
  .end
    display: none
</style>
