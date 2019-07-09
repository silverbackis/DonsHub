<template>
  <div class="columns flex-direction-column layout is-gapless">
    <div class="column is-narrow">
      <nav-bar />
    </div>
    <div class="column page">
      <nuxt />
    </div>
    <div class="column is-narrow">
      <div class="footer">
        <div class="top section">
          <div class="container">
            <nav class="columns is-gapless">
              <ul class="column is-3">
                <li><app-link to="/" :exact="true">Home</app-link></li>
                <li><app-link to="/iploughlane">iPloughLane</app-link></li>
              </ul>
              <ul class="column is-3">
                <li><app-link to="/club-zone">Club Zone</app-link></li>
                <li><app-link to="/fan-zone">Fan Zone</app-link></li>
              </ul>
              <ul class="column is-3">
                <li><app-link to="/donate">Donate</app-link></li>
                <li><app-link to="/about">About Dons Hub</app-link></li>
              </ul>
              <ul class="column is-3">
                <li><app-link to="/contact">Contact</app-link></li>
                <li>
                  <app-link to="/terms-privacy">Terms & Privacy</app-link>
                </li>
              </ul>
            </nav>
          </div>
        </div>
        <div class="content bottom">
          <div class="columns is-gapless">
            <div class="column">
              &copy; {{ new Date().getFullYear() }} Silverback IS
            </div>
            <div class="column is-narrow">
              <app-link to="https://www.silverbackwebapps.com">
                made with <img src="~assets/images/icon-heart.svg" /> by
                Silverback
              </app-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import NavBar from '~/components/NavBar'
import AppLink from '~/components/AppLink'

export default {
  components: {
    AppLink,
    NavBar
  }
}
</script>

<style lang="sass">
@import "assets/sass/utilities"
.layout
  height: 100%
  .page
    background-color: $gold
  .footer
    background: $grey-lightest
    color: $grey
    a
      color: inherit
      &:hover,
      &.is-active
        color: $blue
    .bottom
      background: $white
      padding: .5rem
      font-size: .75rem
</style>
