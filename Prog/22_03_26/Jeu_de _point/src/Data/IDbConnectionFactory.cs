using System.Data.Common;
using System.Threading;
using System.Threading.Tasks;

namespace Data;

public interface IDbConnectionFactory
{
    Task<DbConnection> CreateOpenConnectionAsync(CancellationToken cancellationToken = default);
}
