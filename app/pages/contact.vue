<template>
  <div class="columns flex-direction-column white-page is-gapless">
    <div class="column is-narrow">
      <hero>
        <h1 class="title">
          <img
            src="~assets/images/hero/contact.svg"
            alt="Donate Icon"
            class="icon"
          />
          Contact
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
    title: 'Contact'
  }
}
</script>
