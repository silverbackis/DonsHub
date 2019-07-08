<template>
  <div class="columns flex-direction-column white-page is-gapless">
    <div class="column is-narrow">
      <hero>
        <h1 class="title">
          <img
            src="~assets/images/hero/about-us.svg"
            alt="Donate Icon"
            class="icon"
          />
          About
        </h1>
      </hero>
    </div>
    <div class="column page-content">
      <div class="section">
        <div class="container">
          <div class="content">
            <p>
              Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla leo
              massa, placerat eget eros sit amet, venenatis vulputate magna.
              Phasellus lectus odio, viverra eu diam ac, pulvinar pharetra diam.
              Suspendisse ut mi justo. Sed scelerisque sed nulla ut maximus.
              Pellentesque consequat nec nisl ut gravida. Suspendisse potenti.
              Nunc mattis lacus vel vehicula pulvinar. Aenean convallis semper
              egestas. Fusce et mattis felis. Aenean ut tincidunt dui, ut
              laoreet ligula. Donec maximus nunc id urna dignissim bibendum.
              Duis placerat mattis dolor, at elementum nulla viverra a. Integer
              maximus dolor a lectus tempor bibendum. Cras mollis rhoncus
              vulputate. Proin mi leo, auctor et justo vel, dictum tempus justo.
            </p>
            <div class="has-text-centered">
              <img
                src="~assets/images/iploughlane.svg"
                alt="iPloughLane Road Sign - Made to look like Plough Lane real road sign"
                class="iploughlane-road-sign"
              />
            </div>
            <p>
              Quisque turpis libero, placerat et luctus sit amet, faucibus ut
              tellus. Aliquam erat volutpat. Vestibulum suscipit ligula vitae
              odio cursus, sed accumsan justo fringilla. Donec pellentesque
              convallis dui et sagittis. Morbi dictum libero in elementum
              efficitur. Maecenas suscipit ipsum eros, eu lacinia urna
              sollicitudin nec. Vivamus dignissim justo at eleifend elementum.
              Ut viverra, lacus in tincidunt dictum, tortor risus volutpat
              dolor, vel ultrices justo sapien id risus. Curabitur tincidunt
              neque purus, sed imperdiet est tempor sed. Sed imperdiet interdum
              luctus. Pellentesque a est condimentum, hendrerit ligula viverra,
              feugiat sem. Aenean non massa a ipsum fermentum vehicula.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Hero from '~/components/Hero'

export default {
  components: {
    Hero
  },
  head: {
    title: 'About'
  }
}
</script>

<style lang="sass">
.iploughlane-road-sign
  display: inline-block
  width: 250px
  max-width: 100%
  margin: 2rem 0
</style>
