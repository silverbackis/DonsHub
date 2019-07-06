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
    .icon
      vertical-align: middle
      height: 66px
      margin-right: .4em
      +mobile
        height: 40px
    .white-circle
      background: $white
      border-radius: 50%
      width: 66px
      height: 66px
      overflow: hidden
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
    max-height: 180%
    +mobile
      top: 45%
      max-height: 70%
      transform: translate3d(-50%, -50%, 0)
</style>
