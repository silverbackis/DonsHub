<template>
  <section class="hero is-primary">
    <img
      src="~assets/images/image-sw19.svg"
      alt="SW19 Background Image Text"
      class="sw19"
    />
    <div class="hero-body">
      <div class="container">
        <slot />
      </div>
    </div>
  </section>
</template>

<style lang="sass">
@import 'assets/sass/utilities'
.hero.is-primary
  position: relative
  padding-bottom: 3.5rem
  overflow: visible
  z-index: 0
  .title
    font-family: bebas, 'sans-serif'
    color: $gold
    font-weight: normal
    +tablet
      font-size: 2.8rem
    +desktop
      font-size: 3.2rem
    + .subtitle
      margin-top: 0
      +mobile
        font-size: 1rem
  .sw19
    user-select: none
    pointer-events: none
    position: absolute
    top: -6%
    left: 50%
    transform: translate3d(-50%, 0, 0)
    max-width: 99%
    overflow: hidden
    backface-visibility: hidden
    +mobile
      top: 2%
      max-height: 70%
</style>
