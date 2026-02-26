<footer class="pie">
  <div class="pie-content">
    <p>&copy; 2026 UTT</p>
    <p>
      &copy; Web de prueba realizada como proyecto de estadia
    </p>
  </div>
</footer>

<style>
.pie {
  background-color: #009966;
  color: #fff;
  font-family: 'Arial', sans-serif;
  padding: 20px;
  text-align: center;
  position: relative; /* Changed to relative */
  bottom: 0;
  width: 100%;
  box-shadow: 0px -2px 5px rgba(0, 0, 0, 0.2);
}

.pie-content {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  align-items: center;
  max-width: 1200px; /* Limiting the width */
  margin: 0 auto; /* Centering the content */
}

.pie p {
  margin: 0;
  font-size: 0.9em;
  letter-spacing: 0.05em;
}

.pie a {
  color: #ddd;
  text-decoration: none;
  transition: color 0.3s ease;
  padding: 0 10px;
}

.pie a:hover {
  color: #fff;
  text-decoration: underline;
}

@media (max-width: 768px) {
  .pie-content {
    flex-direction: column;
    text-align: center;
  }

  .pie p {
    margin-bottom: 10px;
  }
}
</style>