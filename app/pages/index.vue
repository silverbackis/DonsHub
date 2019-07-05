<template>
  <section class="gold-page columns is-gapless">
    <div class="column is-narrow">
      <hero>
        <h1 class="title">
          The Home of AFC Wimbledon
          <span class="is-inline-block">
            Fans <span class="has-text-white">#DonsHub</span>
          </span>
        </h1>
        <p class="subtitle">
          Stay up-to-date with the latest new, live feeds, social media and join
          fellow fans on matchday for a chat
        </p>
      </hero>
    </div>
    <div class="column">
      <home-cards />
    </div>
  </section>
</template>

<script>
import Hero from '~/components/Hero'
import HomeCards from '~/components/HomeCards'

export default {
  components: {
    Hero,
    HomeCards
  },
  data: () => ({}),
  head: {
    title: 'Dons Hub'
  }
}
</script>

<style lang="sass">
@import 'assets/sass/utilities'
</style>
