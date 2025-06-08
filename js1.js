<script>
function testVar() {
  if (true) {
    var name = "James";
  }
  console.log(name); // ✅ Works (function scoped)
}
testVar();

</script>