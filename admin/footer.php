  <style>
    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      margin: 0;
    }

    .contenedor-principal {
      flex: 1; 
      padding: 20px; 
    }

    .pie {
      background-color: #009966;
      color: #fff;
      padding: 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-top: 2px solid #666;
      box-shadow: 0px -5px 10px rgba(0, 0, 0, 0.3);
      font-family: 'Arial', sans-serif;
      text-align: center;
    }

    .pie p {
      margin: 0;
      font-size: 1.1em;
      letter-spacing: 0.05em;
    }

    .pie a {
      color: #bbb;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .pie a:hover {
      color: #fff;
      text-decoration: underline;
    }

    @media (max-width: 768px) {
      .pie {
        flex-direction: column;
        text-align: center;
        padding: 20px;
      }

      .pie p {
        margin-bottom: 10px;
      }
    }
  </style>
</head>
<body>
  <footer class="pie">
    <p>Enero 2026</p>
    <p><a href="#">UTT</a></p>
  </footer>