<template>
  <div class="columns flex-direction-column layout is-gapless is-mobile">
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
            <nav
              class="columns is-gapless is-mobile is-multiline footer-links-columns"
            >
              <ul class="column is-6-mobile is-3-tablet">
                <li><app-link to="/" :exact="true">Home</app-link></li>
                <li><app-link to="/iploughlane">iPloughLane</app-link></li>
              </ul>
              <ul class="column is-6-mobile is-3-tablet">
                <li><app-link to="/club-zone">Club Zone</app-link></li>
                <li><app-link to="/fan-zone">Fan Zone</app-link></li>
              </ul>
              <ul class="column is-6-mobile is-3-tablet">
                <li><app-link to="/donate">Donate</app-link></li>
                <li><app-link to="/about">About Dons Hub</app-link></li>
              </ul>
              <ul class="column is-6-mobile is-3-tablet">
                <li><app-link to="/contact">Contact</app-link></li>
                <li>
                  <app-link to="/terms-privacy">Terms & Privacy</app-link>
                </li>
              </ul>
            </nav>
          </div>
        </div>
        <div class="content bottom">
          <div class="columns is-gapless is-centered">
            <div class="column is-narrow">
              <app-link
                to="https://silverbackmail.createsend.com/h/i/C9F967EA2392F3EB"
                class="button is-dark is-rounded is-subscribe"
              >
                Subscribe for updates
              </app-link>
            </div>
          </div>
          <div class="columns is-gapless">
            <div class="column">
              &copy; {{ new Date().getFullYear() }} Dons Hub
            </div>
            <div class="column is-narrow">
              <app-link to="https://www.silverbackwebapps.com">
                made with
                <img src="~assets/images/icon-heart.svg" class="icon-heart" />
                by Silverback
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
    +mobile
      .footer-links-columns
        max-width: 500px
        align-self: center
        margin: auto
    .bottom
      background: $grey-darkest
      color: $grey-light
      padding: 1.5rem .7rem .7rem .7rem
      font-size: .9rem
      a
        color: $grey-light
      +mobile
        text-align: center
      .icon-heart
        margin: 2px 5px -2px 5px
        height: 15px
      .button.is-subscribe
        background: $grey
        color: $white
</style>
