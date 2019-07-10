<template>
  <section class="gold-page columns is-gapless ipl-page">
    <div class="column is-narrow">
      <hero>
        <h1 class="title">i<span class="has-text-white">PloughLane</span></h1>
        <p class="subtitle">
          Virtual Stadium
        </p>
      </hero>
    </div>
    <div class="column">
      <login-cards />
    </div>
  </section>
</template>

<script>
import Hero from '~/components/Hero'
import LoginCards from '~/components/iploughlane/LoginCards'

export default {
  components: {
    Hero,
    LoginCards
  },
  data: () => ({}),
  head: {
    titleTemplate: 'Dons Hub'
  }
}
</script>

<style lang="sass">
@import 'assets/sass/utilities'
.ipl-page .hero.is-primary
  padding-bottom: 3.5rem
  .title
    font-family: inherit
    font-weight: bold
    margin: 0
  .subtitle
    font-size: 1.75rem
  .sw19
    top: -20px
    +mobile
      top: 95px
    max-height: 360px
</style>
