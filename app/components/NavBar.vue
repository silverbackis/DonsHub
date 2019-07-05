<template>
  <div class="default-nav">
    <transition name="menu">
      <nav v-if="isActive" class="menu">
        <div class="inner">
          <div class="columns is-gapless is-mobile">
            <ul class="column is-6">
              <nav-bar-link to="/" exact>
                Home
              </nav-bar-link>
              <nav-bar-link to="/iploughlane">
                iPloughLane
              </nav-bar-link>
              <nav-bar-link to="/club-zone">
                Club Zone
              </nav-bar-link>
              <nav-bar-link to="/fan-zone">
                Fan Zone
              </nav-bar-link>
            </ul>
            <ul class="column is-6">
              <nav-bar-link to="/donate">
                Donate
              </nav-bar-link>
              <nav-bar-link to="/about">
                About
              </nav-bar-link>
              <nav-bar-link to="/contact">
                Contact
              </nav-bar-link>
              <nav-bar-link to="/terms-privacy">
                Terms & Privacy
              </nav-bar-link>
            </ul>
          </div>
        </div>
      </nav>
    </transition>
    <div class="level is-mobile" role="navigation" aria-label="main navigation">
      <div class="level-left">
        <nuxt-link class="level-item logo-text" to="/">
          Dons &nbsp; <span class="has-text-white">Hub</span>
        </nuxt-link>
      </div>
      <div class="level-right">
        <div class="level-item">
          <menu-hamburger class="menu-hamburger" @toggle="toggleMenu" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import NavBarLink from './NavBarLink'
import MenuHamburger from './MenuHamburger'
export default {
  components: { NavBarLink, MenuHamburger },
  data: () => ({
    isActive: false
  }),
  methods: {
    toggleMenu(active) {
      this.isActive = active
    }
  }
}
</script>

<style lang="sass">
@import "assets/sass/utilities"
.menu-enter-active,
.menu-leave-active
  transition: opacity 300ms, transform 300ms
  transform-origin: 100% 0

.menu-enter,
.menu-leave-to
  opacity: 0
  transform: scale(.9, .9)

.default-nav
  padding: 1rem 1.5rem
  position: relative
  z-index: 999
  .logo-text
    font-family: bebas, 'sans-serif'
    font-size: 2rem
    color: $gold !important
  .menu
    position: absolute
    top: 0
    right: 0
    padding: .8rem
    width: 100vw
    max-width: 400px
    z-index: 1
    .inner
      display: block
      position: relative
      width: 100%
      background: $blue
      color: $white
      box-shadow: 2px 3px 4px 2px rgba($black, .5)
      padding: 2.6rem 2rem
      .menu-link
        color: inherit
        &:hover,
        &.is-active
          color: $gold
</style>
